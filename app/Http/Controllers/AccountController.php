<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Account;
use App\Models\Setting;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Exports\AccountsExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AccountController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:account-read|account-create|account-update|account-delete', ['only' => ['index']]);
        $this->middleware('permission:account-create', ['only' => ['create','store']]);
        $this->middleware('permission:account-update', ['only' => ['edit','update']]);
        $this->middleware('permission:account-delete', ['only' => ['destroy']]);
        $this->middleware('permission:account-export', ['only' => ['doExport']]);
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
        $accounts = $this->filter($request)->paginate(10)->withQueryString();
        $currencies = Currency::where('company_id', Session::get('company_id'))->where('enabled', 1)->pluck('name', 'code');
        return view('accounts.index',compact('accounts','currencies'));
    }

    private function filter(Request $request)
    {
        $query = Account::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('name', 'like', '%'.$request->name.'%');

        if($request->number)
            $query->where('number', 'like', '%'.$request->number.'%');

        if($request->currency_code)
            $query->where('currency_code', '=', $request->currency_code);

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
        return Excel::download(new AccountsExport($request, session('company_id')), 'accounts.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currencies = Currency::where('company_id', Session::get('company_id'))->where('enabled', 1)->pluck('name', 'code');
        return view('accounts.create',compact('currencies'));
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
        $data = $request->only(['name','number','currency_code','opening_balance','bank_name','bank_phone','bank_address','enabled']);
        $data['company_id'] = session('company_id');
        DB::transaction(function () use ($data, $request) {
            $account = Account::create($data);
            if($request->default_account == "1") {
                Setting::where('company_id', Session::get('company_id'))->where('key', 'general.default_account')->update(['value' => $account->id]);
            }
        });
        return redirect()->route('account.index')->with('success', trans('Account Added Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        $currencies = Currency::where('company_id', Session::get('company_id'))->where('enabled', 1)->pluck('name', 'code');
        return view('accounts.edit', compact('account','currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        $this->validation($request, $account->id);
        $data = $request->only(['name','number','currency_code','opening_balance','bank_name','bank_phone','bank_address','enabled']);
        DB::transaction(function () use ($data, $account) {
            $account->update($data);
        });
        return redirect()->route('account.index')->with('success', trans('Account Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('account.index')->withSuccess(trans('Your Account Has Been Deleted Successfully'));
    }

    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:255'],
            'currency_code' => ['required', 'string', 'max:55'],
            'opening_balance' => ['required', 'numeric'],
            'enabled' => ['required', 'in:0,1'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255']
        ]);

        if (empty($id)) {
            $request->validate(['default_account' => ['required', 'in:0,1'],]);
        }
    }
}
