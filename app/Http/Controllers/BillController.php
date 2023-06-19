<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Tax;
use App\Models\Bill;
use App\Models\Item;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Account;
use App\Models\Company;
use App\Models\Category;
use App\Models\Currency;
use App\Models\BillItem;
use App\Models\BillTotal;
use Illuminate\Support\Str;
use App\Models\BillHistory;
use App\Models\BillItemTax;
use App\Models\BillPayment;
use Illuminate\Http\Request;
use App\Models\OfflinePayment;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    private $billId;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $bills = $this->filter($request)->paginate(10)->withQueryString();
        return view('bills.index',compact('company','bills'));
    }

    private function filter(Request $request)
    {
        $query = Bill::with('vendor:id,name')->where('company_id', session('company_id'))->latest();
        if ($request->bill_number)
            $query->where('bill_number', 'like', $request->bill_number.'%');

        if($request->amount)
            $query->where('amount', 'like', $request->amount.'%');

        if($request->billed_at)
            $query->where('billed_at', 'like', $request->billed_at.'%');

        return $query;
    }

    /**
     * Generate next bill number
     *
     * @return string
     */
    public function getNextBillNumber($company)
    {
        $prefix = $company->bill_number_prefix;
        $next = $company->bill_number_next;
        $digit = $company->bill_number_digit;
        $number = $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);
        return $number;
    }

    /**
     * Increase the next invoice number
     */
    public function increaseNextBillNumber($company)
    {
        $currentBill = $company->bill_number_next;
        $next = $currentBill + 1;
        DB::table('settings')->where('company_id', $company->id)->where('key', 'general.bill_number_next')->update(['value' => $next]);
    }

    public function getAddPaymentDetails(Request $request)
    {
        $bill = Bill::find($request->i_id);
        $amount = $bill->amount - $bill->paid;
        if($bill) {
            $output = array('payment_amount' =>  $amount);
            return json_encode($output);
        } else {
            return response()->json(['status' => 0]);
        }
    }

    public function addPaymentStore(Request $request)
    {
        $request->validate([
            'bill_id' => ['required', 'integer'],
            'currency_code' => ['required', 'string'],
            'payment_date' => ['required', 'date'],
            'payment_amount' => ['required', 'numeric'],
            'payment_account' => ['required', 'integer'],
            'payment_method' => ['required', 'string'],
            'description' => ['nullable', 'string', 'max:1000']
        ]);

        DB::transaction(function () use ($request) {
            $currencyInfo = Currency::where('company_id', Session::get('company_id'))->where('code', $request->currency_code)->first();
            $data['company_id'] = session('company_id');
            $data['bill_id'] = $request->bill_id;
            $data['account_id'] = $request->payment_account;
            $data['paid_at'] = $request->payment_date;
            $data['amount'] = $request->payment_amount;
            $data['currency_code'] = $request->currency_code;
            $data['currency_rate'] = $currencyInfo->rate;
            $data['description'] = $request->description;
            $data['payment_method'] = $request->payment_method;
            $billPayment = BillPayment::create($data);
            $myBillStatus = $this->billStatusUpdate($request, $currencyInfo);
            $desc_amount = money((float) $billPayment->amount, (string) $billPayment->currency_code, true)->format();
            $historyData = [
                'company_id' => $billPayment->company_id,
                'bill_id' => $billPayment->bill_id,
                'status_code' => $myBillStatus,
                'notify' => '0',
                'description' => $desc_amount . ' ' . "payments",
            ];
            BillHistory::create($historyData);

        });
        return response()->json(['status' => 1]);
    }

    public function billStatusUpdate($request, $currencyInfo)
    {
        $request['currency_code'] = $currencyInfo->code;
        $request['currency_rate'] = $currencyInfo->rate;
        $request['bill_id'] = $request->bill_id;
        $bill = Bill::find($request->bill_id);
        if ($request['currency_code'] == $bill->currency_code) {
            if ($request['payment_amount'] > $bill->amount - $bill->paid) {
                $bill->bill_status_code = 'paid';
            } elseif ($request['payment_amount'] == $bill->amount - $bill->paid) {
                $bill->bill_status_code = 'paid';
            } else {
                $bill->bill_status_code = 'partial';
            }
        } else {
            $request_bill = new Bill();

            $request_bill->amount = (float) $request['payment_amount'];
            $request_bill->currency_code = $currencyInfo->code;
            $request_bill->currency_rate = $currencyInfo->rate;

            $amount = $request_bill->getConvertedAmount();
            if ($amount > $bill->amount - $bill->paid) {
                $bill->bill_status_code = 'paid';
            } elseif ($amount == $bill->amount - $bill->paid) {
                $bill->bill_status_code = 'paid';
            } else {
                $bill->bill_status_code = 'partial';
            }
        }
        $bill->save();

        return $bill->bill_status_code;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $vendors = Vendor::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $currencies = Currency::where('company_id', Session::get('company_id'))->where('enabled', 1)->pluck('name', 'code');
        $currency = Currency::where('company_id', Session::get('company_id'))->where('code', '=', $company->default_currency)->first();
        $items = Item::where('company_id', Session::get('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $taxes = Tax::where('company_id', Session::get('company_id'))->where('enabled', 1)->orderBy('name')->get()->pluck('title', 'id');
        $categories = Category::where('company_id', Session::get('company_id'))->where('enabled', 1)->where('type', 'expense')->orderBy('name')->pluck('name', 'id');
        $number = $this->getNextBillNumber($company);

        return view('bills.create', compact('company','vendors', 'currencies', 'currency', 'items', 'taxes', 'categories','number'));
    }

    public function getBillItems(Request $request)
    {
        $q = $request->q;
        $q_a = explode('_', $request->item_array);

        $data = Item::with('tax:id,rate,type')->where('company_id', Session::get('company_id'))
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                      ->orWhere('sku', 'like', '%' . $q . '%');
        })
        ->whereNotIn('id', $q_a)
        ->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validation($request);

        $vendorInfo = Vendor::findOrFail($request->vendor_id);
        $currencyInfo = Currency::where('company_id', Session::get('company_id'))->where('code', $request->currency_code)->first();

        $data = $request->only(['bill_number','order_number','billed_at','due_at','currency_code','category_id']);
        $data['company_id'] = session('company_id');
        $data['bill_status_code'] = 'draft';
        $data['amount'] = $request->grand_total;
        $data['currency_rate'] = $currencyInfo->rate;
        $data['vendor_id'] = $request->vendor_id;
        $data['vendor_name'] = $vendorInfo->name;
        $data['vendor_email'] = $vendorInfo->email;
        $data['vendor_tax_number'] = $vendorInfo->tax_number;
        $data['vendor_phone'] = $vendorInfo->phone;
        $data['vendor_adress'] = $vendorInfo->address;
        $data['parent_id'] = auth()->user()->id;
        $data['notes'] = $request->description;
        if ($request->picture) {
            $data['attachment'] = $request->picture->store('bill');
        }

        DB::transaction(function () use ($data , $request) {
            $company = Company::findOrFail(Session::get('company_id'));
            $company->setSettings();
            $bill = Bill::create($data);
            $this->billId = $bill->id;
            $taxes = [];
            $tax_total = 0;
            $sub_total = 0;
            if($request->product) {
                $order_row_id = $keys = $request->product['order_row_id'];
                $oquantity = $request->product['order_quantity'];
                foreach ($keys as $id => $key) {
                    $order_quantity = (double) $oquantity[$id];
                    $item = Item::with('tax:id,rate,type,name')->where('company_id', session('company_id'))->where('id', $order_row_id[$id])->first();
                    $item_sku = '';
                    $item_id = !empty($item->id) ? $item->id : 0;
                    $item_amount = (double) $item->purchase_price * (double) $order_quantity;
                    if (!empty($item_id)) {
                        $item_object = Item::find($item_id);
                        $item_sku = $item_object->sku;
                        // Increase stock (item sold)
                        $item_object->quantity += (double) $order_quantity;
                        $item_object->save();
                    } elseif ($item->sku) {
                        $item_sku = $item->sku;
                    }
                    $tax_amount = 0;
                    $tax_amounts = 0;
                    $item_taxes = [];
                    if (!empty($item->tax_id)) {
                        $taxType = $item->tax->type;
                        $taxRate = $item->tax->rate;
                        if ($taxRate !== null && $taxRate != 0) {
                            if ($taxType == "inclusive") {
                                $tax_amount = (double) (($item->purchase_price * $taxRate) / (100 + $taxRate));
                                $tax_amounts = (double) ($tax_amount * $order_quantity);
                                $item_amount -= $tax_amounts;
                                $item_taxes[] = [
                                    'company_id' => session('company_id'),
                                    'bill_id' => $bill->id,
                                    'tax_id' => $item->tax_id,
                                    'name' => $item->tax->name,
                                    'amount' => $tax_amounts,
                                ];
                            } else {
                                $tax_amount = (double) (($item->purchase_price * $taxRate) / 100);
                                $tax_amounts = (double) ($tax_amount * $order_quantity);
                                $item_taxes[] = [
                                    'company_id' => session('company_id'),
                                    'bill_id' => $bill->id,
                                    'tax_id' => $item->tax_id,
                                    'name' => $item->tax->name,
                                    'amount' => $tax_amounts,
                                ];
                            }
                        }
                    }

                    if(!empty($item->tax_id)){
                        $myItemTaxId = $item->tax_id;
                    } else {
                        $myItemTaxId = null;
                    }

                    $bill_item = BillItem::create([
                        'company_id' => session('company_id'),
                        'bill_id' => $bill->id,
                        'item_id' => $item_id,
                        'name' => Str::limit($item->name, 180, ''),
                        'sku' => $item_sku,
                        'quantity' => (double) $order_quantity,
                        'price' => (double) $item->purchase_price,
                        'tax' => $tax_amounts,
                        'tax_id' => $myItemTaxId,
                        'total' => $item_amount,
                    ]);

                    $bill_item->item_taxes = false;

                    // set item_taxes for
                    if (!empty($item->tax_id)) {
                        $bill_item->item_taxes = $item_taxes;
                    }

                    if ($item_taxes) {
                        foreach ($item_taxes as $item_tax) {
                            $item_tax['bill_item_id'] = $bill_item->id;
                            BillItemTax::create($item_tax);

                            // Set taxes
                            if (isset($taxes) && array_key_exists($item_tax['tax_id'], $taxes)) {
                                $taxes[$item_tax['tax_id']]['amount'] += $item_tax['amount'];
                            } else {
                                $taxes[$item_tax['tax_id']] = [
                                    'name' => $item_tax['name'],
                                    'amount' => $item_tax['amount']
                                ];
                            }
                        }
                    }

                    // Calculate totals
                    $tax_total += $bill_item->tax;
                    $sub_total += $bill_item->total;
                }
            }

            $s_total = $sub_total;
            // Apply discount to total
            if ($request->total_discount) {
                $s_discount = $request->total_discount;
                $s_total = $s_total - $s_discount;
            }
            $amount = $s_total + $tax_total;
            $billData['amount'] = $amount;
            $bill->update($billData);

            // Add bill totals
            $this->addTotals($bill, $request, $taxes, $sub_total, $request->total_discount, $tax_total);
            // Add bill history
            BillHistory::create([
                'company_id' => session('company_id'),
                'bill_id' => $bill->id,
                'status_code' => 'draft',
                'notify' => 0,
                'description' => $bill->bill_number." added!",
            ]);

            // Update next bill number
            $this->increaseNextBillNumber($company);
        });

        return redirect()->route('bill.show', $this->billId)->with('success', trans('Bill Added Successfully'));
    }

    public function addTotals($bill, $request, $taxes, $sub_total, $discount_total, $tax_total)
    {
        $sort_order = 1;
        // Added bill sub total
        BillTotal::create([
            'company_id' => session('company_id'),
            'bill_id' => $bill->id,
            'code' => 'sub_total',
            'name' => 'bills.sub_total',
            'amount' => $sub_total,
            'sort_order' => $sort_order,
        ]);
        $sort_order++;
        // Added bill discount
        if ($discount_total > 0) {
            BillTotal::create([
                'company_id' => session('company_id'),
                'bill_id' => $bill->id,
                'code' => 'discount',
                'name' => 'bills.discount',
                'amount' => $discount_total,
                'sort_order' => $sort_order,
            ]);
            // This is for total
            $sub_total = $sub_total - $discount_total;
            $sort_order++;
        }
        // Added bill taxes
        if (isset($taxes)) {
            foreach ($taxes as $tax) {
                BillTotal::create([
                    'company_id' => session('company_id'),
                    'bill_id' => $bill->id,
                    'code' => 'tax',
                    'name' => $tax['name'],
                    'amount' => $tax['amount'],
                    'sort_order' => $sort_order,
                ]);
                $sort_order++;
            }
        }
        // Added bill total
        BillTotal::create([
            'company_id' => session('company_id'),
            'bill_id' => $bill->id,
            'code' => 'total',
            'name' => 'bills.total',
            'amount' => $sub_total + $tax_total,
            'sort_order' => $sort_order,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $salesMan = User::find(auth()->user()->id);
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $payment_methods = OfflinePayment::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'code');
        return view('bills.show', compact('company','salesMan','bill','accounts','payment_methods'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function edit(Bill $bill)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $vendors = Vendor::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $currencies = Currency::where('company_id', Session::get('company_id'))->where('enabled', 1)->pluck('name', 'code');
        $currency = Currency::where('company_id', Session::get('company_id'))->where('code', '=', $company->default_currency)->first();
        $items = Item::where('company_id', Session::get('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $taxes = Tax::where('company_id', Session::get('company_id'))->where('enabled', 1)->orderBy('name')->get()->pluck('title', 'id');
        $categories = Category::where('company_id', Session::get('company_id'))->where('enabled', 1)->where('type', 'expense')->orderBy('name')->pluck('name', 'id');
        $number = $this->getNextBillNumber($company);

        return view('bills.edit', compact('company','vendors', 'currencies', 'currency', 'items', 'bill', 'taxes', 'categories','number'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bill $bill)
    {
        $this->validation($request, $bill->id);
        $this->billId = $bill->id;

        $vendorInfo = Vendor::findOrFail($request->vendor_id);
        $currencyInfo = Currency::where('company_id', Session::get('company_id'))->where('code', $request->currency_code)->first();

        $data = $request->only(['order_number','billed_at','due_at','currency_code','category_id']);
        $data['company_id'] = session('company_id');
        $data['bill_status_code'] = 'draft';
        $data['amount'] = $request->grand_total;
        $data['currency_rate'] = $currencyInfo->rate;
        $data['vendor_id'] = $request->vendor_id;
        $data['vendor_name'] = $vendorInfo->name;
        $data['vendor_email'] = $vendorInfo->email;
        $data['vendor_tax_number'] = $vendorInfo->tax_number;
        $data['vendor_phone'] = $vendorInfo->phone;
        $data['vendor_adress'] = $vendorInfo->address;
        $data['parent_id'] = auth()->user()->id;
        $data['notes'] = $request->description;
        if ($request->picture) {
            $data['attachment'] = $request->picture->store('bill');
        }

        DB::transaction(function () use ($data , $request, $bill, $currencyInfo) {
            $company = Company::findOrFail(Session::get('company_id'));
            $company->setSettings();

            // increase stock (item update)
            foreach($bill->items as $item) {
                $itemIncreaseObject = Item::find($item->item_id);
                $itemIncreaseObject->quantity -= $item->quantity;
                $itemIncreaseObject->save();
            }

            $this->deforeUpdateDelete($bill->id);

            $bill = Bill::findOrFail($bill->id);
            $bill->update($data);

            $taxes = [];
            $tax_total = 0;
            $sub_total = 0;
            if($request->product) {
                $order_row_id = $keys = $request->product['order_row_id'];
                $oquantity = $request->product['order_quantity'];
                foreach ($keys as $id => $key) {
                    $order_quantity = (double) $oquantity[$id];
                    $item = Item::with('tax:id,rate,type,name')->where('id', $order_row_id[$id])->first();
                    $item_sku = '';
                    $item_id = !empty($item->id) ? $item->id : 0;
                    $item_amount = (double) $item->purchase_price * (double) $order_quantity;

                    if (!empty($item_id)) {
                        $item_object = Item::find($item_id);
                        $item_sku = $item_object->sku;
                        // Decrease stock (item sold)
                        $item_object->quantity += (double) $order_quantity;
                        $item_object->save();
                    } elseif ($item->sku) {
                        $item_sku = $item->sku;
                    }

                    $tax_amount = 0;
                    $tax_amounts = 0;
                    $item_taxes = [];
                    if (!empty($item->tax_id)) {
                        $taxType = $item->tax->type;
                        $taxRate = $item->tax->rate;
                        if ($taxRate !== null && $taxRate != 0) {
                            if ($taxType == "inclusive") {
                                $tax_amount = (double) (($item->purchase_price * $taxRate) / (100 + $taxRate));
                                $tax_amounts = (double) ($tax_amount * $order_quantity);
                                $item_amount -= $tax_amounts;
                                $item_taxes[] = [
                                    'company_id' => session('company_id'),
                                    'bill_id' => $bill->id,
                                    'tax_id' => $item->tax_id,
                                    'name' => $item->tax->name,
                                    'amount' => $tax_amounts,
                                ];
                            } else {
                                $tax_amount = (double) (($item->purchase_price * $taxRate) / 100);
                                $tax_amounts = (double) ($tax_amount * $order_quantity);
                                $item_taxes[] = [
                                    'company_id' => session('company_id'),
                                    'bill_id' => $bill->id,
                                    'tax_id' => $item->tax_id,
                                    'name' => $item->tax->name,
                                    'amount' => $tax_amounts,
                                ];
                            }
                        }
                    }

                    if(!empty($item->tax_id)){
                        $myItemTaxId = $item->tax_id;
                    } else {
                        $myItemTaxId = null;
                    }

                    $bill_item = BillItem::create([
                        'company_id' => session('company_id'),
                        'bill_id' => $bill->id,
                        'item_id' => $item_id,
                        'name' => Str::limit($item->name, 180, ''),
                        'sku' => $item_sku,
                        'quantity' => (double) $order_quantity,
                        'price' => (double) $item->purchase_price,
                        'tax' => $tax_amounts,
                        'tax_id' => $myItemTaxId,
                        'total' => $item_amount,
                    ]);

                    $bill_item->item_taxes = false;

                    // set item_taxes for
                    if (!empty($item->tax_id)) {
                        $bill_item->item_taxes = $item_taxes;
                    }

                    if ($item_taxes) {
                        foreach ($item_taxes as $item_tax) {
                            $item_tax['bill_item_id'] = $bill_item->id;
                            BillItemTax::create($item_tax);

                            // Set taxes
                            if (isset($taxes) && array_key_exists($item_tax['tax_id'], $taxes)) {
                                $taxes[$item_tax['tax_id']]['amount'] += $item_tax['amount'];
                            } else {
                                $taxes[$item_tax['tax_id']] = [
                                    'name' => $item_tax['name'],
                                    'amount' => $item_tax['amount']
                                ];
                            }
                        }
                    }

                    // Calculate totals
                    $tax_total += $bill_item->tax;
                    $sub_total += $bill_item->total;

                }
            }

            $s_total = $sub_total;
            // Apply discount to total
            if ($request->total_discount) {
                $s_discount = $request->total_discount;
                $s_total = $s_total - $s_discount;
            }

            $amount = $s_total + $tax_total;
            $billData['amount'] = $amount;
            $bill->update($billData);

            // Add invoice totals
            $this->addTotals($bill, $request, $taxes, $sub_total, $request->total_discount, $tax_total);

            $this->checkStatusUpdate($bill, $currencyInfo);

        });

        return redirect()->route('bill.show', $this->billId)->with('success', trans('Bill Updated Successfully'));
    }

    public function checkStatusUpdate($bill, $currencyInfo)
    {
        $request['currency_code'] = $currencyInfo->code;
        $request['currency_rate'] = $currencyInfo->rate;
        $request['bill_id'] = $bill->id;
        $bill = Bill::findOrFail($bill->id);
        if ($request['currency_code'] == $bill->currency_code) {
            if($bill->amount == $bill->paid) {
                $bill->bill_status_code = 'paid';
            } elseif ($bill->amount > $bill->paid) {
                $bill->bill_status_code = 'partial';
            } elseif ($bill->amount < $bill->paid) {
                $bill->bill_status_code = 'paid';
            } elseif($bill->paid == '0') {
                $bill->bill_status_code = 'draft';
            } else {
                $bill->bill_status_code = 'draft';
            }
        } else {
            $request_bill = new Bill();

            $request_bill->amount = (float) $bill->paid;
            $request_bill->currency_code = $currencyInfo->code;
            $request_bill->currency_rate = $currencyInfo->rate;

            $amount = $request_bill->getConvertedAmount();
            if($bill->amount == $bill->paid) {
                $bill->bill_status_code = 'paid';
            } elseif ($bill->amount > $bill->paid) {
                $bill->bill_status_code = 'partial';
            } elseif ($bill->amount < $bill->paid) {
                $bill->bill_status_code = 'paid';
            } elseif($bill->paid == '0') {
                $bill->bill_status_code = 'draft';
            } else {
                $bill->bill_status_code = 'draft';
            }
        }
        $bill->save();
        return $bill->bill_status_code;
    }

    public function deforeUpdateDelete($id = 0)
    {
        $invoice = Bill::findOrFail($id);
        DB::table('bill_items')->where('bill_id', $id)->delete();
        DB::table('bill_item_taxes')->where('bill_id', $id)->delete();

        foreach ($invoice->totals as $total) {
            if($total->code == 'sub_total')
                DB::table('bill_totals')->where('bill_id', $id)->where('code', 'sub_total')->delete();

            if($total->code == 'tax')
                DB::table('bill_totals')->where('bill_id', $id)->where('code', 'tax')->delete();

            if($total->code == 'discount')
                DB::table('bill_totals')->where('bill_id', $id)->where('code', 'discount')->delete();

            if($total->code == 'total')
                DB::table('bill_totals')->where('bill_id', $id)->where('code', 'total')->delete();
        }

        return $invoice->id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $bill)
    {
        // Decrease stock
        $bill->items()->each(function ($bill_item) {
            $item = Item::find($bill_item->item_id);

            if (empty($item)) {
                return;
            }

            $item->quantity += (double) $bill_item->quantity;
            $item->save();
        });

        $this->deleteRelationships($bill, ['items', 'item_taxes', 'histories', 'payments', 'totals']);
        $bill->delete();
        return redirect()->route('bill.index')->with('success', trans('Bill Deleted Successfully'));
    }

    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'vendor_id' => ['required', 'integer'],
            'currency_code' => ['required', 'string'],
            'billed_at' => ['required', 'date'],
            'due_at' => ['required', 'date'],
            'bill_number' => ['required', 'string', 'unique:bills,bill_number,' . $id],
            'order_number' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer'],
            'grand_total' => ['required', 'numeric'],
            'total_discount' => ['nullable', 'numeric'],
            'total_tax' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string', 'max:1000'],
            'picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $request->validate([
            "product"    => "required|array",
            "product.*"  => "required",
            "product.order_row_id.*"  => "required",
            "product.order_quantity.*"  => "required",
        ]);
    }
}
