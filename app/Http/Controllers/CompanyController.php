<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\User;
use App\Models\Account;
use App\Models\Company;
use App\Models\Setting;
use App\Models\Currency;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\InvoiceStatus;
use App\Models\OfflinePayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:company-read|company-create|company-update|company-delete', ['only' => ['index']]);
        $this->middleware('permission:company-create', ['only' => ['create','store']]);
        $this->middleware('permission:company-update', ['only' => ['edit','update']]);
        $this->middleware('permission:company-delete', ['only' => ['destroy']]);
        $this->middleware('permission:company-export', ['only' => ['doExport']]);
    }

    public function companyAccountSwitch(Request $request)
    {
        $companySwitch = $request->company_switch;
        session(['company_id' => $companySwitch]);
        User::where('id', auth()->user()->id)->update(['company_id' => session('company_id')]);

        return redirect()->back();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = $this->filter($request)->paginate(10)->withQueryString();
        foreach ($companies as $company) {
            $company->setSettings();
        }
        return view('companies.index', compact('companies'));
    }

    private function filter(Request $request)
    {
        $query = Company::latest();
        if ($request->company_domain)
            $query->where('domain', 'like', '%'.$request->company_domain.'%');

        if ($request->company_name){
            $query->whereHas('settings', function($q) use ($request) {
                $q->where('value', 'like', '%'.$request->company_name.'%');
            });
        }

        if($request->company_email){
            $query->whereHas('settings', function($q) use ($request) {
                $q->where('value', 'like', '%'.$request->company_email.'%');
            });
        }
        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currencies = array(
            "USD"=>"US Dollar",
            "EUR"=>"Euro",
            "GBP"=>"British Pound",
            "TRY"=>"Turkish Lira",
            "INR"=>"Indian Rupee"
        );
        return view('companies.create', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'company_name' => 'required',
            'company_email' => 'required',
            'domain' => 'required',
            'default_currency' => 'required',
            'enabled' => 'required',
        ]);

        $logoUrl='';
        if($request->hasFile('photo'))
        {
            $this->validate($request,['photo' => 'image|mimes:png,jpg,jpeg']);
            $logo = $request->photo;
            $logoNewName = time().$logo->getClientOriginalName();
            $logo->move('lara/companies',$logoNewName);
            $logoUrl = 'lara/companies/'.$logoNewName;
        }

        $company = Company::create([
            'domain' => $request->input('domain'),
            'enabled' => $request->input('enabled')
        ]);
        $company->users()->attach(auth()->user()->id);
        $account = Account::create([
            'company_id' => $company->id,
            'name' => 'Cash',
            'number' => '1',
            'currency_code' => $request->input('default_currency'),
            'bank_name' => 'Cash',
            'enabled' => '1',
        ]);
        $invoiceStatusRows = [
            [
                'company_id' => $company->id,
                'name' => 'Draft',
                'code' => 'draft'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Sent',
                'code' => 'sent'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Viewed',
                'code' => 'viewed'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Approved',
                'code' => 'approved'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Partial',
                'code' => 'partial'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Paid',
                'code' => 'paid'
            ],
        ];

        foreach ($invoiceStatusRows as $row) {
            InvoiceStatus::create($row);
        }

        $categoriesRows = [
            [
                'company_id' => $company->id,
                'name' => 'Transfer',
                'type' => 'other',
                'color' => '#605ca8',
                'enabled' => '1'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Deposit',
                'type' => 'income',
                'color' => '#f39c12',
                'enabled' => '1'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Sales',
                'type' => 'income',
                'color' => '#6da252',
                'enabled' => '1'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Other',
                'type' => 'expense',
                'color' => '#d2d6de',
                'enabled' => '1'
            ],
            [
                'company_id' => $company->id,
                'name' => 'General',
                'type' => 'item',
                'color' => '#00c0ef',
                'enabled' => '1'
            ],
        ];

        foreach ($categoriesRows as $row) {
            Category::create($row);
        }

        $offlinePaymentRows = [
            [
                'company_id' => $company->id,
                'name' => 'Cash',
                'code' => 'offlinepayment.cash.1',
                'show_to_customer' => '0',
                'order' => '1'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Bank Transfer',
                'code' => 'offlinepayment.bank_transfer.2',
                'show_to_customer' => '0',
                'order' => '2'
            ]
        ];

        foreach ($offlinePaymentRows as $row) {
            OfflinePayment::create($row);
        }

        $currencyRows = [
            [
                'company_id' => $company->id,
                'name' => 'Indian Rupee',
                'code' => 'INR',
                'rate' => '1.00',
                'enabled' => '1',
                'precision' => config('money.INR.precision'),
                'symbol' => config('money.INR.symbol'),
                'symbol_first' => config('money.INR.symbol_first'),
                'decimal_mark' => config('money.INR.decimal_mark'),
                'thousands_separator' => config('money.INR.thousands_separator'),
            ],
            [
                'company_id' => $company->id,
                'name' => 'US Dollar',
                'code' => 'USD',
                'rate' => '1.00',
                'enabled' => '1',
                'precision' => config('money.USD.precision'),
                'symbol' => config('money.USD.symbol'),
                'symbol_first' => config('money.USD.symbol_first'),
                'decimal_mark' => config('money.USD.decimal_mark'),
                'thousands_separator' => config('money.USD.thousands_separator'),
            ],
            [
                'company_id' => $company->id,
                'name' => 'Euro',
                'code' => 'EUR',
                'rate' => '1.25',
                'enabled' => '1',
                'precision' => config('money.EUR.precision'),
                'symbol' => config('money.EUR.symbol'),
                'symbol_first' => config('money.EUR.symbol_first'),
                'decimal_mark' => config('money.EUR.decimal_mark'),
                'thousands_separator' => config('money.EUR.thousands_separator'),
            ],
            [
                'company_id' => $company->id,
                'name' => 'British Pound',
                'code' => 'GBP',
                'rate' => '1.60',
                'enabled' => '1',
                'precision' => config('money.GBP.precision'),
                'symbol' => config('money.GBP.symbol'),
                'symbol_first' => config('money.GBP.symbol_first'),
                'decimal_mark' => config('money.GBP.decimal_mark'),
                'thousands_separator' => config('money.GBP.thousands_separator'),
            ],
            [
                'company_id' => $company->id,
                'name' => 'Turkish Lira',
                'code' => 'TRY',
                'rate' => '0.80',
                'enabled' => '1',
                'precision' => config('money.TRY.precision'),
                'symbol' => config('money.TRY.symbol'),
                'symbol_first' => config('money.TRY.symbol_first'),
                'decimal_mark' => config('money.TRY.decimal_mark'),
                'thousands_separator' => config('money.TRY.thousands_separator'),
            ]
        ];

        foreach ($currencyRows as $row) {
            Currency::create($row);
        }

        $rows = [
            [
                'company_id' => $company->id,
                'key' => 'general.company_name',
                'value' => $request->input('company_name'),
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.company_email',
                'value' => $request->input('company_email'),
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.default_locale',
                'value' => 'en-GB',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.default_account',
                'value' => $account->id,
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.financial_start',
                'value' => Carbon::now()->format('d-m'),
            ],

            [
                'company_id' => $company->id,
                'key' => 'general.timezone',
                'value' => 'Europe/London',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.date_format',
                'value' => 'd M Y',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.date_separator',
                'value' => 'space',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.percent_position',
                'value' => 'after',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.bill_number_prefix',
                'value' => 'BILL-',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.bill_number_digit',
                'value' => '5',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.bill_number_next',
                'value' => '1',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.bill_item',
                'value' => 'settings.bill.item',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.invoice_number_prefix',
                'value' => 'SAN-',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.invoice_number_digit',
                'value' => '6',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.invoice_number_next',
                'value' => '1',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.invoice_item',
                'value' => 'settings.invoice.item',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.invoice_price',
                'value' => 'settings.invoice.price',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.invoice_quantity',
                'value' => 'settings.invoice.quantity',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.send_item_reminder',
                'value' => '1',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.schedule_item_stocks',
                'value' => '3,5,7',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.default_payment_method',
                'value' => 1,
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.email_protocol',
                'value' => 'mail',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.email_sendmail_path',
                'value' => '/usr/sbin/sendmail -bs',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.schedule_time',
                'value' => '09:00',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.admin_theme',
                'value' => 'skin-green-light',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.list_limit',
                'value' => '25',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.use_gravatar',
                'value' => '0',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.session_handler',
                'value' => 'file',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.session_lifetime',
                'value' => '30',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.file_size',
                'value' => '2',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.file_types',
                'value' => 'pdf,jpeg,jpg,png',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.wizard',
                'value' => '0',
            ],
        ];

        foreach ($rows as $row) {
            Setting::create($row);
        }

        if($request->input('address'))
        {
            $addressRow = [
                [
                    'company_id' => $company->id,
                    'key' => 'general.company_address',
                    'value' => $request->input('address'),
                ],
            ];

            foreach ($addressRow as $row) {
                Setting::create($row);
            }
        }

        if($request->input('default_currency'))
        {
            $defaultCurrencyRow = [
                [
                    'company_id' => $company->id,
                    'key' => 'general.default_currency',
                    'value' => $request->input('default_currency'),
                ],
            ];

            foreach ($defaultCurrencyRow as $row) {
                Setting::create($row);
            }
        }

        if($logoUrl != "")
        {

            $photoRows = [
                [
                    'company_id' => $company->id,
                    'key' => 'general.company_logo',
                    'value' => $logoUrl,
                ],
            ];

            foreach ($photoRows as $row) {
                Setting::create($row);
            }
        }

        session()->flash('success', trans('Company Created Successfully'));
        return redirect()->route('company.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $company->setSettings();
        $currencies = Currency::where('company_id',$company->id)->pluck('name', 'code');

        return view('companies.edit',compact('company','currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $this->validate($request, [
            'company_name' => 'required',
            'company_email' => 'required',
            'domain' => 'required',
            'enabled' => 'required',
        ]);

        $logoUrl = "";
        if($request->hasFile('photo'))
        {
            $this->validate($request,['photo' => 'image|mimes:png,jpg,jpeg']);
            $logo = $request->photo;
            $logoNewName = time().$logo->getClientOriginalName();
            $logo->move('lara/companies',$logoNewName);
            $logoUrl = 'lara/companies/'.$logoNewName;
        }
        DB::beginTransaction();
        try {
            $company->domain = $request->input('domain');
            $company->enabled = $request->input('enabled');
            $company->save();

            DB::table('settings')->where('company_id', $company->id)
                ->where('key', 'general.company_name')
                ->update(['value' => $request->input('company_name')]);

            DB::table('settings')->where('company_id', $company->id)
                ->where('key', 'general.company_email')
                ->update(['value' => $request->input('company_email')]);

            if($logoUrl != "")
            {
                if (Setting::where('key', 'general.company_logo')->where('company_id', $company->id)->count() > 0) {
                    DB::table('settings')->where('company_id', $company->id)->where('key', 'general.company_logo')->update(['value' => $logoUrl]);
                } else {
                    Setting::create(['company_id' => $company->id, 'key' => 'general.company_logo', 'value' => $logoUrl]);
                }

            }

            if (Setting::where('key', '=', 'general.company_address')->where('company_id', $company->id)->count() > 0) {
                DB::table('settings')->where('company_id', $company->id)
                    ->where('key', 'general.company_address')
                    ->update(['value' => $request->address]);
            } else {
                Setting::create(['company_id' => $company->id, 'key' => 'general.company_address', 'value' => $request->address]);
            }

            DB::commit();
            return redirect()->route('company.index')->with('success', trans('Company Updated Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        if ($company->id == session('company_id')) {
            return redirect('company')->with('error',trans('This Is Active Company'));
        }

        DB::beginTransaction();
        try {
            $company->delete();
            DB::table('settings')->where('company_id', $company->id)->delete();
            DB::table('currencies')->where('company_id', $company->id)->delete();
            DB::commit();
            return redirect()->route('company.index')->with('success',trans('Company Deleted Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}
