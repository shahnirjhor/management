<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Account;
use App\Models\BillPayment;
use App\Models\Company;
use App\Models\InvoicePayment;
use App\Models\Payment;
use App\Models\Revenue;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:transaction-read', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $transactions = $this->filter($request);
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        return view('transactions.index',compact('transactions','company','accounts'));
    }

    private function filter(Request $request)
    {
        $type = null;
        $paymentTransactions = [];
        $billPaymentTransactions = [];
        if ($type != 'income') {
            $paymentsQuery = Payment::orderBy('id', 'DESC')->where('company_id', Session::get('company_id'));
            $billQaymentsQuery = BillPayment::orderBy('id', 'DESC')->where('company_id', Session::get('company_id'));
            if ($request->paid_at) {
                $paymentsQuery->where('paid_at', 'like', $request->paid_at.'%');
                $billQaymentsQuery->where('paid_at', 'like', $request->paid_at.'%');
            }

            if ($request->account_id) {
                $paymentsQuery->where('account_id', $request->account_id);
                $billQaymentsQuery->where('account_id', $request->account_id);
            }

            if ($request->amount) {
                $paymentsQuery->where('amount', $request->amount);
                $billQaymentsQuery->where('account_id', $request->amount);
            }

            $payments = $paymentsQuery->paginate(5);
            $billQayments = $billQaymentsQuery->paginate(5);

            $paymentTransactions = $this->addTransactions($payments, "Expense");
            $billPaymentTransactions = $this->addTransactions($billQayments, "Expense");
        }

        $revenueTransactions = [];
        $invoicePaymentTransactions = [];
        if ($type != 'expense') {
            $revenuesQuery = Revenue::orderBy('id', 'DESC')->where('company_id', Session::get('company_id'));
            $invoicePaymentQuery = InvoicePayment::orderBy('id', 'DESC')->where('company_id', Session::get('company_id'));
            if ($request->paid_at) {
                $revenuesQuery->where('paid_at', 'like', $request->paid_at.'%');
                $invoicePaymentQuery->where('paid_at', 'like', $request->paid_at.'%');
            }

            if ($request->account_id) {
                $revenuesQuery->where('account_id', $request->account_id);
                $invoicePaymentQuery->where('account_id', $request->account_id);
            }

            if ($request->amount) {
                $revenuesQuery->where('amount', $request->amount);
                $invoicePaymentQuery->where('account_id', $request->amount);
            }

            $revenues = $revenuesQuery->paginate(5);
            $invoicePayments = $invoicePaymentQuery->paginate(5);

            $revenueTransactions = $this->addTransactions($revenues, "Expense");
            $invoicePaymentTransactions = $this->addTransactions($invoicePayments, "Expense");
        }

        $myTransactions = array_merge($paymentTransactions,$billPaymentTransactions, $revenueTransactions, $invoicePaymentTransactions);

        return $myTransactions;
    }

    protected function addTransactions($items, $type)
    {
        $transactions = [];
        foreach ($items as $item) {
            $transactions[] = [
                'paid_at'           => date("d M Y", strtotime($item->paid_at)),
                'account_name'      => $item->account->name,
                'type'              => $type,
                'description'       => $item->description,
                'amount'            => $item->amount,
                'currency_code'     => $item->currency_code,
                'category_name'     => $item->category->name
            ];
        }
        return $transactions;
    }
}
