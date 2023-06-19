<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Tax;
use App\Models\Item;
use App\Models\User;
use App\Models\Account;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Currency;
use App\Models\InvoiceItem;
use App\Models\InvoiceTotal;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InvoiceItemTax;
use App\Models\InvoiceHistory;
use App\Models\InvoicePayment;
use App\Models\OfflinePayment;
use App\Exports\InvoicesExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\Item as ItemNotification;
use App\Notifications\ItemReminder as ItemReminderNotification;

class InvoiceController extends Controller
{
    private $invoiceId;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->export)
            return $this->doExport($request);
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $invoices = $this->filter($request)->paginate(10)->withQueryString();
        return view('invoices.index',compact('company','invoices'));
    }

    /**
     * Performs exporting
     *
     * @param Request $request
     * @return void
     */
    private function doExport(Request $request)
    {
        return Excel::download(new InvoicesExport($request), 'invoices.xlsx');
    }

    private function filter(Request $request)
    {
        $query = Invoice::with('customer:id,name')->where('company_id', session('company_id'))->latest();
        if ($request->invoice_number)
            $query->where('invoice_number', 'like', $request->invoice_number.'%');
        if($request->amount)
            $query->where('amount', 'like', $request->amount.'%');
        if($request->invoiced_at)
            $query->where('invoiced_at', 'like', $request->invoiced_at.'%');

        return $query;
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
        $customers = Customer::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $currencies = Currency::where('company_id', Session::get('company_id'))->where('enabled', 1)->pluck('name', 'code');
        $currency = Currency::where('company_id', Session::get('company_id'))->where('code', '=', $company->default_currency)->first();
        $items = Item::where('company_id', Session::get('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $taxes = Tax::where('company_id', Session::get('company_id'))->where('enabled', 1)->orderBy('name')->get()->pluck('title', 'id');
        $categories = Category::where('company_id', Session::get('company_id'))->where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id');
        $number = $this->getNextInvoiceNumber($company);

        return view('invoices.create', compact('company','customers', 'currencies', 'currency', 'items', 'taxes', 'categories','number'));
    }

    public function generateItemData(Request $request)
    {
        $this->validate($request,[
            'itemId' => 'required'
        ]);
        $item = Item::where('company_id', Session::get('company_id'))->where('enabled', 1)->where('id', $request->itemId)->first();
        if($item) {
            $response['status']  = '1';
            $response['quantity'] = 1;
        } else {
            $response['status']  = '0';
            $response['quantity'] = 0;
        }
        return $response;
    }

    public function getItems(Request $request)
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
     * Generate next invoice number
     *
     * @return string
     */
    public function getNextInvoiceNumber($company)
    {
        $prefix = $company->invoice_number_prefix;
        $next = $company->invoice_number_next;
        $digit = $company->invoice_number_digit;
        $number = $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);
        return $number;
    }

    /**
     * Increase the next invoice number
     */
    public function increaseNextInvoiceNumber($company)
    {
        $currentInvoice = $company->invoice_number_next;
        $next = $currentInvoice + 1;

        DB::table('settings')->where('company_id', $company->id)
                ->where('key', 'general.invoice_number_next')
                ->update(['value' => $next]);
    }

    public function getAddPaymentDetails(Request $request)
    {
        $invoice = Invoice::find($request->i_id);
        $amount = $invoice->amount - $invoice->paid;
        if($invoice) {
            $output = array('payment_amount' =>  $amount);
            return json_encode($output);
        } else {
            return response()->json(['status' => 0]);
        }
    }

    public function addPaymentStore(Request $request)
    {
        $request->validate([
            'invoice_id' => ['required', 'integer'],
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
            $data['invoice_id'] = $request->invoice_id;
            $data['account_id'] = $request->payment_account;
            $data['paid_at'] = $request->payment_date;
            $data['amount'] = $request->payment_amount;
            $data['currency_code'] = $request->currency_code;
            $data['currency_rate'] = $currencyInfo->rate;
            $data['description'] = $request->description;
            $data['payment_method'] = $request->payment_method;
            $invoicePayment = InvoicePayment::create($data);
            $myInvoiceStatus = $this->invoiceStatusUpdate($request, $currencyInfo);
            $desc_amount = money((float) $invoicePayment->amount, (string) $invoicePayment->currency_code, true)->format();
            $historyData = [
                'company_id' => $invoicePayment->company_id,
                'invoice_id' => $invoicePayment->invoice_id,
                'status_code' => $myInvoiceStatus,
                'notify' => '0',
                'description' => $desc_amount . ' ' . "payments",
            ];
            InvoiceHistory::create($historyData);
        });
        return response()->json(['status' => 1]);
    }

    public function invoiceStatusUpdate($request, $currencyInfo)
    {
        $request['currency_code'] = $currencyInfo->code;
        $request['currency_rate'] = $currencyInfo->rate;
        $request['invoice_id'] = $request->invoice_id;
        $invoice = Invoice::find($request->invoice_id);
        if ($request['currency_code'] == $invoice->currency_code) {
            if ($request['payment_amount'] > $invoice->amount - $invoice->paid) {
                $invoice->invoice_status_code = 'paid';
            } elseif ($request['payment_amount'] == $invoice->amount - $invoice->paid) {
                $invoice->invoice_status_code = 'paid';
            } else {
                $invoice->invoice_status_code = 'partial';
            }
        } else {
            $request_invoice = new Invoice();

            $request_invoice->amount = (float) $request['payment_amount'];
            $request_invoice->currency_code = $currencyInfo->code;
            $request_invoice->currency_rate = $currencyInfo->rate;

            $amount = $request_invoice->getConvertedAmount();
            if ($amount > $invoice->amount - $invoice->paid) {
                $invoice->invoice_status_code = 'paid';
            } elseif ($amount == $invoice->amount - $invoice->paid) {
                $invoice->invoice_status_code = 'paid';
            } else {
                $invoice->invoice_status_code = 'partial';
            }
        }
        $invoice->save();

        return $invoice->invoice_status_code;
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

        $customerInfo = Customer::findOrFail($request->customer_id);
        $currencyInfo = Currency::where('company_id', Session::get('company_id'))->where('code', $request->currency_code)->first();

        $data = $request->only(['invoice_number','order_number','invoiced_at','due_at','currency_code','category_id']);
        $data['company_id'] = session('company_id');
        $data['invoice_status_code'] = 'draft';
        $data['amount'] = $request->grand_total;
        $data['currency_rate'] = $currencyInfo->rate;
        $data['customer_id'] = $request->customer_id;
        $data['customer_name'] = $customerInfo->name;
        $data['customer_email'] = $customerInfo->email;
        $data['customer_tax_number'] = $customerInfo->tax_number;
        $data['customer_phone'] = $customerInfo->phone;
        $data['customer_adress'] = $customerInfo->address;
        $data['parent_id'] = auth()->user()->id;
        $data['notes'] = $request->description;
        if ($request->picture) {
            $data['attachment'] = $request->picture->store('invoice');
        }

        DB::transaction(function () use ($data , $request) {
            $company = Company::findOrFail(Session::get('company_id'));
            $company->setSettings();
            $invoice = Invoice::create($data);
            $this->invoiceId = $invoice->id;
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
                    $item_amount = (double) $item->sale_price * (double) $order_quantity;
                    if (!empty($item_id)) {
                        $item_object = Item::find($item_id);
                        $item_sku = $item_object->sku;
                        // Decrease stock (item sold)
                        $item_object->quantity -= (double) $order_quantity;
                        $item_object->save();

                        if ($company->send_item_reminder) {
                            $item_stocks = explode(',', $company->schedule_item_stocks);
                            foreach ($item_stocks as $item_stock) {
                                if ($item_object->quantity == $item_stock) {
                                    foreach ($item_object->company->users as $user) {
                                        $user->notify(new ItemReminderNotification($item_object));
                                    }
                                }
                            }
                        }

                        if ($item_object->quantity == 0) {
                            foreach ($item_object->company->users as $user) {
                                $user->notify(new ItemNotification($item_object));
                            }
                        }

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
                                $tax_amount = (double) (($item->sale_price * $taxRate) / (100 + $taxRate));
                                $tax_amounts = (double) ($tax_amount * $order_quantity);
                                $item_amount -= $tax_amounts;
                                $item_taxes[] = [
                                    'company_id' => session('company_id'),
                                    'invoice_id' => $invoice->id,
                                    'tax_id' => $item->tax_id,
                                    'name' => $item->tax->name,
                                    'amount' => $tax_amounts,
                                ];
                            } else {
                                $tax_amount = (double) (($item->sale_price * $taxRate) / 100);
                                $tax_amounts = (double) ($tax_amount * $order_quantity);
                                $item_taxes[] = [
                                    'company_id' => session('company_id'),
                                    'invoice_id' => $invoice->id,
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

                    $invoice_item = InvoiceItem::create([
                        'company_id' => session('company_id'),
                        'invoice_id' => $invoice->id,
                        'item_id' => $item_id,
                        'name' => Str::limit($item->name, 180, ''),
                        'sku' => $item_sku,
                        'quantity' => (double) $order_quantity,
                        'price' => (double) $item->sale_price,
                        'tax' => $tax_amounts,
                        'tax_id' => $myItemTaxId,
                        'total' => $item_amount,
                    ]);

                    $invoice_item->item_taxes = false;

                    // set item_taxes for
                    if (!empty($item->tax_id)) {
                        $invoice_item->item_taxes = $item_taxes;
                    }

                    if ($item_taxes) {
                        foreach ($item_taxes as $item_tax) {
                            $item_tax['invoice_item_id'] = $invoice_item->id;
                            InvoiceItemTax::create($item_tax);

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
                    $tax_total += $invoice_item->tax;
                    $sub_total += $invoice_item->total;
                }
            }

            $s_total = $sub_total;
            // Apply discount to total
            if ($request->total_discount) {
                $s_discount = $request->total_discount;
                $s_total = $s_total - $s_discount;
            }
            $amount = $s_total + $tax_total;
            $invoiceData['amount'] = $amount;
            $invoice->update($invoiceData);

            // Add invoice totals
            $this->addTotals($invoice, $request, $taxes, $sub_total, $request->total_discount, $tax_total);
            // Add invoice history
            InvoiceHistory::create([
                'company_id' => session('company_id'),
                'invoice_id' => $invoice->id,
                'status_code' => 'draft',
                'notify' => 0,
                'description' => $invoice->invoice_number." added!",
            ]);

            // Update next invoice number
            $this->increaseNextInvoiceNumber($company);

        });

        return redirect()->route('invoice.show', $this->invoiceId)->with('success', trans('Invoice Added Successfully'));


    }

    public function addTotals($invoice, $request, $taxes, $sub_total, $discount_total, $tax_total)
    {
        $sort_order = 1;
        // Added invoice sub total
        InvoiceTotal::create([
            'company_id' => session('company_id'),
            'invoice_id' => $invoice->id,
            'code' => 'sub_total',
            'name' => 'invoices.sub_total',
            'amount' => $sub_total,
            'sort_order' => $sort_order,
        ]);
        $sort_order++;
        // Added invoice discount
        if ($discount_total > 0) {
            InvoiceTotal::create([
                'company_id' => session('company_id'),
                'invoice_id' => $invoice->id,
                'code' => 'discount',
                'name' => 'invoices.discount',
                'amount' => $discount_total,
                'sort_order' => $sort_order,
            ]);
            // This is for total
            $sub_total = $sub_total - $discount_total;
            $sort_order++;
        }
        // Added invoice taxes
        if (isset($taxes)) {
            foreach ($taxes as $tax) {
                InvoiceTotal::create([
                    'company_id' => session('company_id'),
                    'invoice_id' => $invoice->id,
                    'code' => 'tax',
                    'name' => $tax['name'],
                    'amount' => $tax['amount'],
                    'sort_order' => $sort_order,
                ]);
                $sort_order++;
            }
        }
        // Added invoice total
        InvoiceTotal::create([
            'company_id' => session('company_id'),
            'invoice_id' => $invoice->id,
            'code' => 'total',
            'name' => 'invoices.total',
            'amount' => $sub_total + $tax_total,
            'sort_order' => $sort_order,
        ]);
    }

    public function checkStatusUpdate($invoice, $currencyInfo)
    {
        $request['currency_code'] = $currencyInfo->code;
        $request['currency_rate'] = $currencyInfo->rate;
        $request['invoice_id'] = $invoice->id;
        $invoice = Invoice::findOrFail($invoice->id);
        if ($request['currency_code'] == $invoice->currency_code) {
            if($invoice->amount == $invoice->paid) {
                $invoice->invoice_status_code = 'paid';
            } elseif ($invoice->amount > $invoice->paid) {
                $invoice->invoice_status_code = 'partial';
            } elseif ($invoice->amount < $invoice->paid) {
                $invoice->invoice_status_code = 'paid';
            } elseif($invoice->paid == '0') {
                $invoice->invoice_status_code = 'draft';
            } else {
                $invoice->invoice_status_code = 'draft';
            }
        } else {
            $request_invoice = new Invoice();

            $request_invoice->amount = (float) $invoice->paid;
            $request_invoice->currency_code = $currencyInfo->code;
            $request_invoice->currency_rate = $currencyInfo->rate;

            $amount = $request_invoice->getConvertedAmount();
            if($invoice->amount == $invoice->paid) {
                $invoice->invoice_status_code = 'paid';
            } elseif ($invoice->amount > $invoice->paid) {
                $invoice->invoice_status_code = 'partial';
            } elseif ($invoice->amount < $invoice->paid) {
                $invoice->invoice_status_code = 'paid';
            } elseif($invoice->paid == '0') {
                $invoice->invoice_status_code = 'draft';
            } else {
                $invoice->invoice_status_code = 'draft';
            }
        }
        $invoice->save();
        return $invoice->invoice_status_code;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $salesMan = User::find(auth()->user()->id);
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $payment_methods = OfflinePayment::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'code');
        return view('invoices.show', compact('company','salesMan','invoice','accounts','payment_methods'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $customers = Customer::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $currencies = Currency::where('company_id', Session::get('company_id'))->where('enabled', 1)->pluck('name', 'code');
        $currency = Currency::where('company_id', Session::get('company_id'))->where('code', '=', $company->default_currency)->first();
        $items = Item::where('company_id', Session::get('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $taxes = Tax::where('company_id', Session::get('company_id'))->where('enabled', 1)->orderBy('name')->get()->pluck('title', 'id');
        $categories = Category::where('company_id', Session::get('company_id'))->where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id');
        $number = $this->getNextInvoiceNumber($company);

        return view('invoices.edit', compact('company','customers', 'currencies', 'currency', 'items', 'invoice', 'taxes', 'categories','number'));
    }

    public function deforeUpdateDelete($id = 0)
    {
        $invoice = Invoice::findOrFail($id);
        DB::table('invoice_items')->where('invoice_id', $id)->delete();
        DB::table('invoice_item_taxes')->where('invoice_id', $id)->delete();

        foreach ($invoice->totals as $total) {
            if($total->code == 'sub_total')
                DB::table('invoice_totals')->where('invoice_id', $id)->where('code', 'sub_total')->delete();

            if($total->code == 'tax')
                DB::table('invoice_totals')->where('invoice_id', $id)->where('code', 'tax')->delete();

            if($total->code == 'discount')
                DB::table('invoice_totals')->where('invoice_id', $id)->where('code', 'discount')->delete();

            if($total->code == 'total')
                DB::table('invoice_totals')->where('invoice_id', $id)->where('code', 'total')->delete();
        }

        return $invoice->id;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        $this->validation($request, $invoice->id);
        $this->invoiceId = $invoice->id;

        $customerInfo = Customer::findOrFail($request->customer_id);
        $currencyInfo = Currency::where('company_id', Session::get('company_id'))->where('code', $request->currency_code)->first();

        $data = $request->only(['order_number','invoiced_at','due_at','currency_code','category_id']);
        $data['company_id'] = session('company_id');
        $data['invoice_status_code'] = 'draft';
        $data['amount'] = $request->grand_total;
        $data['currency_rate'] = $currencyInfo->rate;
        $data['customer_id'] = $request->customer_id;
        $data['customer_name'] = $customerInfo->name;
        $data['customer_email'] = $customerInfo->email;
        $data['customer_tax_number'] = $customerInfo->tax_number;
        $data['customer_phone'] = $customerInfo->phone;
        $data['customer_adress'] = $customerInfo->address;
        $data['parent_id'] = auth()->user()->id;
        $data['notes'] = $request->description;
        if ($request->picture) {
            $data['attachment'] = $request->picture->store('invoice');
        }

        DB::transaction(function () use ($data , $request, $invoice, $currencyInfo) {
            $company = Company::findOrFail(Session::get('company_id'));
            $company->setSettings();

            // increase stock (item update)
            foreach($invoice->items as $item) {
                $itemIncreaseObject = Item::find($item->item_id);
                $itemIncreaseObject->quantity += $item->quantity;
                $itemIncreaseObject->save();
            }

            $this->deforeUpdateDelete($invoice->id);

            $invoice = Invoice::findOrFail($invoice->id);
            $invoice->update($data);

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
                    $item_amount = (double) $item->sale_price * (double) $order_quantity;

                    if (!empty($item_id)) {
                        $item_object = Item::find($item_id);
                        $item_sku = $item_object->sku;
                        // Decrease stock (item sold)
                        $item_object->quantity -= (double) $order_quantity;
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
                                $tax_amount = (double) (($item->sale_price * $taxRate) / (100 + $taxRate));
                                $tax_amounts = (double) ($tax_amount * $order_quantity);
                                $item_amount -= $tax_amounts;
                                $item_taxes[] = [
                                    'company_id' => session('company_id'),
                                    'invoice_id' => $invoice->id,
                                    'tax_id' => $item->tax_id,
                                    'name' => $item->tax->name,
                                    'amount' => $tax_amounts,
                                ];
                            } else {
                                $tax_amount = (double) (($item->sale_price * $taxRate) / 100);
                                $tax_amounts = (double) ($tax_amount * $order_quantity);
                                $item_taxes[] = [
                                    'company_id' => session('company_id'),
                                    'invoice_id' => $invoice->id,
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

                    $invoice_item = InvoiceItem::create([
                        'company_id' => session('company_id'),
                        'invoice_id' => $invoice->id,
                        'item_id' => $item_id,
                        'name' => Str::limit($item->name, 180, ''),
                        'sku' => $item_sku,
                        'quantity' => (double) $order_quantity,
                        'price' => (double) $item->sale_price,
                        'tax' => $tax_amounts,
                        'tax_id' => $myItemTaxId,
                        'total' => $item_amount,
                    ]);

                    $invoice_item->item_taxes = false;

                    // set item_taxes for
                    if (!empty($item->tax_id)) {
                        $invoice_item->item_taxes = $item_taxes;
                    }

                    if ($item_taxes) {
                        foreach ($item_taxes as $item_tax) {
                            $item_tax['invoice_item_id'] = $invoice_item->id;
                            InvoiceItemTax::create($item_tax);

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
                    $tax_total += $invoice_item->tax;
                    $sub_total += $invoice_item->total;
                }
            }

            $s_total = $sub_total;
            // Apply discount to total
            if ($request->total_discount) {
                $s_discount = $request->total_discount;
                $s_total = $s_total - $s_discount;
            }
            $amount = $s_total + $tax_total;
            $invoiceData['amount'] = $amount;
            $invoice->update($invoiceData);

            // Add invoice totals
            $this->addTotals($invoice, $request, $taxes, $sub_total, $request->total_discount, $tax_total);

            $this->checkStatusUpdate($invoice, $currencyInfo);

        });

        return redirect()->route('invoice.show', $this->invoiceId)->with('success', trans('Invoice Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        // Increase stock
        $invoice->items()->each(function ($invoice_item) {
            $item = Item::find($invoice_item->item_id);

            if (empty($item)) {
                return;
            }

            $item->quantity += (double) $invoice_item->quantity;
            $item->save();
        });

        $this->deleteRelationships($invoice, ['items', 'item_taxes', 'histories', 'payments', 'totals']);
        $invoice->delete();
        return redirect()->route('invoice.index')->with('success', trans('Invoice Deleted Successfully'));
    }

    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'customer_id' => ['required', 'integer'],
            'currency_code' => ['required', 'string'],
            'invoiced_at' => ['required', 'date'],
            'due_at' => ['required', 'date'],
            'invoice_number' => ['required', 'string', 'unique:invoices,invoice_number,' . $id],
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
