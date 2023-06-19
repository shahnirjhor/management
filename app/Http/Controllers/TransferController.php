<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Account;
use App\Models\Revenue;
use App\Models\Payment;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Category;
use App\Models\Transfer;
use App\Models\OfflinePayment;
use Illuminate\Http\Request;
use App\Exports\TransfersExport;
use Maatwebsite\Excel\Facades\Excel;

class TransferController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:transfer-read|transfer-create|transfer-update|transfer-delete', ['only' => ['index']]);
        $this->middleware('permission:transfer-create', ['only' => ['create','store']]);
        $this->middleware('permission:transfer-update', ['only' => ['edit','update']]);
        $this->middleware('permission:transfer-delete', ['only' => ['destroy']]);
        $this->middleware('permission:transfer-export', ['only' => ['doExport']]);
    }

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
        $transfers = $this->filter($request)->paginate(10);
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        return view('transfers.index',compact('transfers','company','accounts'));
    }

    private function filter(Request $request)
    {
        $query = Transfer::with(['payment', 'payment.account', 'revenue', 'revenue.account'])
            ->whereHas('payment', function($q) use ($request) {
                if ($request->paid_at) {
                    $q->where('paid_at', 'like', $request->paid_at.'%');
                }
                if ($request->from_account) {
                    $q->where('account_id', $request->from_account);
                }

            })->whereHas('revenue', function($r) use ($request) {
                if ($request->to_account) {
                    $r->where('account_id', $request->to_account);
                }
            })->where('transfers.company_id', session('company_id'))->latest();

        return $query;
    }

    /**
     * Performs exporting
     *
     * @param Request $request
     * @return void
     */
    private function doExport(Request $request)
    {
        return Excel::download(new TransfersExport($request, session('company_id')), 'transfers.xlsx');
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
        $currency = Currency::where('code', '=', $company->default_currency)->first();
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $payment_methods = OfflinePayment::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'code');
        return view('transfers.create', compact('company', 'currency', 'accounts', 'payment_methods'));
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
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $currencies = Currency::where('company_id', session('company_id'))->where('enabled', 1)->pluck('rate', 'code')->toArray();

        $payment_currency_code = Account::where('id', $request->from_account)->pluck('currency_code')->first();
        $revenue_currency_code = Account::where('id', $request->to_account)->pluck('currency_code')->first();

        $payment_request = [
            'company_id' => Session::get('company_id'),
            'account_id' => $request->from_account,
            'paid_at' => $request->date,
            'currency_code' => $payment_currency_code,
            'currency_rate' => $currencies[$payment_currency_code],
            'amount' => $request->amount,
            'vendor_id' => 0,
            'description' => $request->description,
            'category_id' => Category::transfer(),
            'payment_method' => $request->payment_method,
            'reference' => $request->reference,
        ];
        $payment = Payment::create($payment_request);

        if ($payment_currency_code != $revenue_currency_code) {

            $default_currency = $company->default_currency;
            $default_amount = $request->amount;
            if ($default_currency != $payment_currency_code) {
                $default_amount_model = new Transfer();
                $default_amount_model->default_currency_code = $default_currency;
                $default_amount_model->amount = $request->amount;
                $default_amount_model->currency_code = $payment_currency_code;
                $default_amount_model->currency_rate = $currencies[$payment_currency_code];
                $default_amount = $default_amount_model->getDivideConvertedAmount();
            }
            $transfer_amount = new Transfer();
            $transfer_amount->default_currency_code = $payment_currency_code;
            $transfer_amount->amount = $default_amount;
            $transfer_amount->currency_code = $revenue_currency_code;
            $transfer_amount->currency_rate = $currencies[$revenue_currency_code];
            $amount = $transfer_amount->getDynamicConvertedAmount();

        } else {
            $amount = $request->amount;
        }

        $revenue_request = [
            'company_id' => Session::get('company_id'),
            'account_id' => $request->to_account,
            'paid_at' => $request->date,
            'currency_code' => $revenue_currency_code,
            'currency_rate' => $currencies[$revenue_currency_code],
            'amount' => $amount,
            'customer_id' => 0,
            'description' => $request->description,
            'category_id' => Category::transfer(),
            'payment_method' => $request->payment_method,
            'reference' => $request->reference,
        ];
        $revenue = Revenue::create($revenue_request);

        $transfer_request = [
            'company_id' => Session::get('company_id'),
            'payment_id' => $payment->id,
            'revenue_id' => $revenue->id,
        ];
        Transfer::create($transfer_request);
        return redirect()->route('transfer.index')->withSuccess(trans('Transfers Information Inserted Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function show(Transfer $transfer)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function edit(Transfer $transfer)
    {
        $payment = Payment::findOrFail($transfer->payment_id);
        $revenue = Revenue::findOrFail($transfer->revenue_id);
        $transfer['from_account_id'] = $payment->account_id;
        $transfer['to_account_id'] = $revenue->account_id;
        $transfer['transferred_at'] = date('Y-m-d', strtotime($payment->paid_at));
        $transfer['description'] = $payment->description;
        $transfer['amount'] = $payment->amount;
        $transfer['payment_method'] = $payment->payment_method;
        $transfer['reference'] = $payment->reference;
        $account = Account::find($payment->account_id);
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $payment_methods = OfflinePayment::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'code');
        $currency = Currency::where('code', '=', $account->currency_code)->first();
        return view('transfers.edit', compact('transfer', 'accounts', 'payment_methods', 'currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transfer $transfer)
    {
        $this->validation($request);
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $currencies = Currency::where('company_id', session('company_id'))->where('enabled', 1)->pluck('rate', 'code')->toArray();

        $payment_currency_code = Account::where('id', $request->from_account)->pluck('currency_code')->first();
        $revenue_currency_code = Account::where('id', $request->to_account)->pluck('currency_code')->first();

        $payment = Payment::findOrFail($transfer->payment_id);
        $revenue = Revenue::findOrFail($transfer->revenue_id);

        $payment_request = [
            'company_id' => Session::get('company_id'),
            'account_id' => $request->from_account,
            'paid_at' => $request->date,
            'currency_code' => $payment_currency_code,
            'currency_rate' => $currencies[$payment_currency_code],
            'amount' => $request->amount,
            'vendor_id' => 0,
            'description' => $request->description,
            'category_id' => Category::transfer(),
            'payment_method' => $request->payment_method,
            'reference' => $request->reference,
        ];
        $payment->update($payment_request);

        // Convert amount if not same currency
        if ($payment_currency_code != $revenue_currency_code) {
            $default_currency = $company->default_currency;
            $default_amount = $request->amount;

            if ($default_currency != $payment_currency_code) {
                $default_amount_model = new Transfer();

                $default_amount_model->default_currency_code = $default_currency;
                $default_amount_model->amount = $request->amount;
                $default_amount_model->currency_code = $payment_currency_code;
                $default_amount_model->currency_rate = $currencies[$payment_currency_code];
                $default_amount = $default_amount_model->getDivideConvertedAmount();
            }

            $transfer_amount = new Transfer();

            $transfer_amount->default_currency_code = $payment_currency_code;
            $transfer_amount->amount = $default_amount;
            $transfer_amount->currency_code = $revenue_currency_code;
            $transfer_amount->currency_rate = $currencies[$revenue_currency_code];

            $amount = $transfer_amount->getDynamicConvertedAmount();
        } else {
            $amount = $request['amount'];
        }

        $revenue_request = [
            'company_id' => Session::get('company_id'),
            'account_id' => $request->to_account,
            'paid_at' => $request->date,
            'currency_code' => $revenue_currency_code,
            'currency_rate' => $currencies[$revenue_currency_code],
            'amount' => $amount,
            'customer_id' => 0,
            'description' => $request->description,
            'category_id' => Category::transfer(),
            'payment_method' => $request->payment_method,
            'reference' => $request->reference,
        ];

        $revenue->update($revenue_request);
        $transfer_request = [
            'company_id' => Session::get('company_id'),
            'payment_id' => $payment->id,
            'revenue_id' => $revenue->id,
        ];
        $transfer->update($transfer_request);
        return redirect()->route('transfer.index')->withSuccess(trans('Transfers Information Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transfer $transfer)
    {
        $transfer->delete();
        return redirect()->route('transfer.index')->with('success', trans('Transfers Deleted Successfully'));
    }

    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'from_account' => ['required', 'integer'],
            'to_account' => ['required', 'integer'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date_format:Y-m-d H:i'],
            'payment_method' => ['required', 'string', 'max:255']
        ]);
    }
}
