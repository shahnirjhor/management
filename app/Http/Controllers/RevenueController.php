<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Revenue;
use App\Models\Category;
use App\Models\OfflinePayment;
use Illuminate\Http\Request;
use App\Exports\RevenuesExport;
use Maatwebsite\Excel\Facades\Excel;

class RevenueController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:revenue-read|revenue-create|revenue-update|revenue-delete', ['only' => ['index']]);
        $this->middleware('permission:revenue-create', ['only' => ['create','store']]);
        $this->middleware('permission:revenue-update', ['only' => ['edit','update']]);
        $this->middleware('permission:revenue-delete', ['only' => ['destroy']]);
        $this->middleware('permission:revenue-export', ['only' => ['doExport']]);
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
        $revenues = $this->filter($request)->paginate(10);
        $customers = Customer::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id');
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        return view('revenues.index',compact('revenues','customers','categories','accounts','company'));
    }

    private function filter(Request $request)
    {
        $query = Revenue::where('company_id', session('company_id'))->latest();

        if ($request->paid_at)
            $query->where('paid_at', 'like', $request->paid_at.'%');

        if ($request->customer_id)
            $query->where('customer_id', $request->customer_id);

        if ($request->category_id)
            $query->where('category_id', $request->category_id);

        if ($request->account_id)
            $query->where('account_id', $request->account_id);

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
        return Excel::download(new RevenuesExport($request, session('company_id')), 'revenues.xlsx');
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
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $customers = Customer::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id');
        $payment_methods = OfflinePayment::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'code');
        $account_currency_code = Account::where('id', $company->default_account)->pluck('currency_code')->first();
        $currency = Currency::where('code', $account_currency_code)->first();
        return view('revenues.create', compact('company','accounts','customers','categories','payment_methods','account_currency_code','currency'));
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
        $data = $request->only(['paid_at','amount','account_id','customer_id','category_id','payment_method','description','attachment','currency_code','currency_rate']);
        $data['company_id'] = session('company_id');
        Revenue::create($data);
        return redirect()->route('revenue.index')->with('success', trans('Revenue Added Successfully'));
    }

    public function validation(Request $request, $id = 0)
    {
        $request->validate([
            'paid_at' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'max:255'],
            'account_id' => ['required', 'integer'],
            'customer_id' => ['required', 'integer'],
            'category_id' => ['required', 'integer'],
            'payment_method' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'attachment' => ['nullable', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
            'currency_code' => ['required', 'string', 'max:255'],
            'currency_rate' => ['required', 'string', 'max:255']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Revenue  $revenue
     * @return \Illuminate\Http\Response
     */
    public function show(Revenue $revenue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Revenue  $revenue
     * @return \Illuminate\Http\Response
     */
    public function edit(Revenue $revenue)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $customers = Customer::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id');
        $payment_methods = OfflinePayment::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'code');
        $account_currency_code = Account::where('id', $company->default_account)->pluck('currency_code')->first();
        $currency = Currency::where('code', $account_currency_code)->first();
        return view('revenues.edit', compact('company','accounts','customers','categories','payment_methods','account_currency_code','currency','revenue'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Revenue  $revenue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Revenue $revenue)
    {
        $this->validation($request);
        $data = $request->only(['paid_at','amount','account_id','customer_id','category_id','payment_method','description','attachment','currency_code','currency_rate']);
        $revenue->update($data);
        return redirect()->route('revenue.index')->with('success', trans('Revenue Edit Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Revenue  $revenue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Revenue $revenue)
    {
        $revenue->delete();
        return redirect()->route('revenue.index')->withSuccess(trans('Your Revenue Has Been Deleted Successfully'));
    }
}
