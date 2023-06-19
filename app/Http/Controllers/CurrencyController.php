<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Company;
use App\Models\Setting;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Exports\CurrenciesExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\PayUService\Exception;

class CurrencyController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:currencies-read|currencies-create|currencies-update|currencies-delete', ['only' => ['index']]);
        $this->middleware('permission:currencies-create', ['only' => ['create','store']]);
        $this->middleware('permission:currencies-update', ['only' => ['edit','update']]);
        $this->middleware('permission:currencies-delete', ['only' => ['destroy']]);
        $this->middleware('permission:currencies-export', ['only' => ['doExport']]);
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
        $currencies = $this->filter($request)->paginate(10)->withQueryString();
        return view('currencies.index',compact('currencies'));
    }

    private function filter(Request $request)
    {
        $query = Currency::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('name', 'like', '%'.$request->name.'%');

        if($request->code)
            $query->where('code', 'like', '%'.$request->code.'%');

        if($request->symbol)
            $query->where('symbol', '=', $request->symbol);

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
        return Excel::download(new CurrenciesExport($request, session('company_id')), 'currencies.xlsx');
    }

    /**
     * Show data for the specified resource.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function code(Request $request)
    {
        $json = new \stdClass();
        $code = request('code');
        if ($code) {
            $currency = config('money.' . $code);
            $currency['rate'] = isset($currency['rate']) ? $currency['rate'] : null;
            $currency['symbol_first'] = $currency['symbol_first'] ? 1 : 0;
            $json = (object) $currency;
        }
        return response()->json($json);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currencies = config('money');
        return view('currencies.create')->with('data', $currencies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request['default_currency']) {
            $request['rate'] = '1';
        }
        $validatedData = $request->validate([
            'name' => 'required',
            'code' => 'required',
            'rate' => 'required',
            'precision' => 'required',
            'symbol' => 'required',
            'symbol_first' => 'required',
            'decimal_mark' => 'required',
            'thousands_separator' => 'required',
            'enabled' => 'required',
        ]);

        /**
         * Method to call db transaction
         */
        DB::beginTransaction();
        try {
            $data = new Currency;
            $data->company_id = session('company_id');
            $data->name = $request->name;
            $data->code = $request->code;
            $data->rate = $request->rate;
            $data->precision = $request->precision;
            $data->symbol = $request->symbol;
            $data->symbol_first = $request->symbol_first;
            $data->decimal_mark = $request->decimal_mark;
            $data->thousands_separator = $request->thousands_separator;
            $data->enabled = $request->enabled;
            $data->save();

            if ($request->default_currency) {
                Setting::where('company_id', session('company_id'))
                ->where('key', 'general.default_currency')
                ->update(['value' => $data->code]);
            }

            DB::commit();
            return redirect()->route('currency.index')->withSuccess(trans('Currency Information Inserted Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Currency $currency)
    {
        $currencies = config('money');
        $data = $currency;
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        return view('currencies.edit', compact('data', 'currencies', 'company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        if ($request['default_currency']) {
            $request['rate'] = '1';
        }
        $validatedData = $request->validate([
            'id' => 'required',
            'name' => 'required',
            'code' => 'required',
            'rate' => 'required',
            'precision' => 'required',
            'symbol' => 'required',
            'symbol_first' => 'required',
            'decimal_mark' => 'required',
            'thousands_separator' => 'required',
            'enabled' => 'required',
        ]);

        /**
         * Method to call db transaction
         */
        $data = $currency;
        DB::beginTransaction();
        try {
            $data->company_id = session('company_id');
            $data->name = $request->name;
            $data->code = $request->code;
            $data->rate = $request->rate;
            $data->precision = $request->precision;
            $data->symbol = $request->symbol;
            $data->symbol_first = $request->symbol_first;
            $data->decimal_mark = $request->decimal_mark;
            $data->thousands_separator = $request->thousands_separator;
            $data->enabled = $request->enabled;
            $data->save();
            if ($request->default_currency) {
                Setting::where('company_id', session('company_id'))
                ->where('key', 'general.default_currency')
                ->update(['value' => $data->code]);
            }
            DB::commit();
            return redirect()->route('currency.index')->withSuccess(trans('Currency Information Updated Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        $data = $currency;
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        if($company->default_currency == $data->code) {
            return redirect()->route('currency.index')->withErrors(trans('You Can Not Delete Default Currency'));
        }

       $data->delete();

       return redirect()->route('currency.index')->withSuccess(trans('Your Currency Has Been Deleted Successfully'));
    }
}
