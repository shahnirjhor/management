<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\Models\Tax;
use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\BankingPayment;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Crypt;

/**
 * Class DatatablesController
 * @package App\Http\Controllers
 * @category Controller
 */
class DatatablesController extends Controller
{
    /**
     * Display a listing of the roles resource
     *
     * @access public
     * @return mixed
     * @throws \Exception
     */
    public function rolesList()
    {
        $roles = Role::orderBy('id', 'DESC')->get();
        return datatables()
            ->of($roles)
            ->addIndexColumn()
            ->addColumn('action', function($row)
            {
                $btn='';
                $roleName = Auth::user()->getRoleNames();
//                if ($row->name != "Super Admin")
//                {
                    if (Auth::user()->hasPermissionTo('role-update'))
                    {
                        $btn .= '<a href="'.route('roles.edit',['role' => $row->id]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp';
                    }
//                }

//                if ($row->is_default != "1")
//                {
                    if (Auth::user()->hasPermissionTo('role-delete'))
                    {

                        $btn .= '<a href="#" data-href="'.route('roles.customDestroy',['id' => $row->id]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>';
                    }
//                }
                return $btn;
            })
            ->addColumn('default', function($row)
            {
                if ($row->is_default == '1')
                {
                    $btn = '<span class="badge badge-pill badge-info">'.trans('Yes').'</span>';
                } else {
                    $btn = '<span class="badge badge-pill badge-danger">'.trans('No').'</span>';
                }
                return $btn;
            })
            ->addColumn('price', function($row)
            {
                if(is_null($row->price)) {
                    return 'N/A';
                } else {
                    return $row->price;
                }
            })
            ->addColumn('validity', function($row)
            {
                if(is_null($row->validity)) {
                    return 'N/A';
                } else {
                    return $row->validity . ' '. trans('Days');;
                }
            })
            ->addColumn('role_for', function($row)
            {
                if ($row->role_for == '1')
                {
                    $btn = '<span class="badge badge-pill badge-info">'.trans('Staff').'</span>';
                } else {
                    $btn = '<span class="badge badge-pill badge-primary">'.trans('User').'</span>';
                }
                return $btn;
            })
            ->rawColumns(['action','default','role_for','validity'])
            ->make(true);
    }

    /**
     * Display a listing of the users resource
     *
     * @access public
     * @return mixed
     * @throws \Exception
     */
    public function userList()
    {
//        $employeeUserIds = Employee::select('user_id')->get()->toArray();
//        $data = User::orderBy('id','DESC')->whereNotIn('id', $employeeUserIds)->get();
        $data = User::orderBy('id','DESC')->get();
        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row)
            {
                $btn = '';
//                if (Auth::user()->hasPermissionTo('user-update'))
//                {
                    $btn .= '<a href="'.route('users.edit',['user' => $row->id]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp';
//                }
                $deleteUser = trans('Are You Sure You Want To Delete This User');
//                if (Auth::user()->hasPermissionTo('user-delete')) {
                    $btn .= '<a href="#" data-href="'.route('users.destroy',['id' => $row->id]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>';
//                }
                return $btn;

            })
            ->addColumn('role', function($row)
            {
                $btn = "";
                if (!empty($row->getRoleNames()))
                {
                    foreach ($row->getRoleNames() as $roleName)
                    {
                        $btn = $roleName;
                    }
                }
                return $btn;
            })
            ->addColumn('status', function($row)
            {
                if($row->status == 0)
                {
                    $btn = '<span class="badge badge-danger">'.trans('Inactive').'</span>';
                } else {
                    $btn = '<span class="badge badge-success">'.trans('Active').'</span>';
                }
                return $btn;
            })
            ->addColumn('register_date', function($row)
            {
                $register_date = date("d M Y", strtotime($row->created_at));
                return $register_date;
            })
            ->rawColumns(['action','status', 'role'])
            ->make(true);
    }





    /**
     * Display a listing of the companies resource
     *
     * @access public
     * @return mixed
     * @throws \Exception
     */
    public function companiesList()
    {
        $companies = Auth::user()->companies()->get();
        foreach ($companies as $company) {
            $company->setSettings();
        }

        return datatables()
            ->of($companies)
            ->addIndexColumn()
            ->addColumn('name', function($row)
            {
                return $row->company_name;
            })
            ->addColumn('email', function($row)
            {
                return $row->company_email;
            })
            ->addColumn('created', function($row)
            {
                return date($row->date_format, strtotime($row->created_at));
            })
            ->addColumn('status', function($row)
            {
                if ($row->enabled == '1')
                {
                    $btn = '<span class="badge badge-pill badge-success">'.trans('Enabled').'</span>';
                } else {
                    $btn = '<span class="badge badge-pill badge-danger">'.trans('Disabled').'</span>';
                }
                return $btn;
            })
            ->addColumn('action', function($row){
                $btn='';
                $btn .= '<a href="'.route('company.edit',['id' => Crypt::encrypt($row->id)]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp';
                $btn .= '<a href="#" data-href="'.route('company.destroy',['id' => encrypt($row->id)]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>';
                return $btn;
            })

            ->rawColumns(['name','domain','email','created','status','action'])
            ->make(true);
    }


    /**
     * Display a listing of the currency resource
     *
     * @access public
     * @return mixed
     * @throws \Exception
     */
    public function currencyList()
    {
        $currency = Currency::where('company_id', Session::get('company_id'))->orderBy('created_at', 'DESC')->get();

        return datatables()
            ->of($currency)
            ->addIndexColumn()
            ->addColumn('action', function($row)
            {
                $btn = '';
                // if (Auth::user()->hasPermissionTo('loan-edit'))
                // {

                    $btn .= '<a href="'.route('currency.edit',['currency' => Crypt::encrypt($row->id)]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp';

                // }
                // if (Auth::user()->hasPermissionTo('loan-delete'))
                // {

                    $btn .= '<a href="#" data-href="'.route('currency.delete',['id' => encrypt($row->id)]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>';


                // }
                return $btn;
            })
            ->addColumn('name', function ($row)
            {
                return $row->name;
            })
            ->addColumn('code', function ($row)
            {
                return $row->code;
            })
            ->addColumn('rate', function ($row)
            {
                return  $row->rate;
            })
            ->addColumn('symbol', function ($row)
            {
                return  '<p style="text-align : center; font-weight : bold; font-size : 18px">'.$row->symbol.'</p>';
            })
            ->addColumn('enabled', function ($row)
            {
                if ($row->enabled == '1')
                {
                    $btn = '<span class="badge badge-pill badge-success">'.trans('Enabled').'</span>';
                } else {
                    $btn = '<span class="badge badge-pill badge-danger">'.trans('Disabled').'</span>';
                }

                return $btn;
            })
            ->rawColumns(['action','name','code','rate','symbol','enabled','precision'])
            ->make(true);
    }

    /**
     * Display a listing of the loan resource
     *
     * @access public
     * @return mixed
     * @throws \Exception
     */
    public function categoryList()
    {
        $category = Category::where('company_id', Session::get('company_id'))->orderBy('created_at', 'DESC')->get();

        return datatables()
            ->of($category)
            ->addIndexColumn()
            ->addColumn('action', function($row)
            {
                $btn = '';
                // if (Auth::user()->hasPermissionTo('loan-edit'))
                // {

                    $btn .= '<a href="'.route('category.edit',['category' => encrypt($row->id)]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp';
                // }
                // if (Auth::user()->hasPermissionTo('loan-delete'))
                // {
                    $btn .= '<a href="#" data-href="'.route('category.delete',['id' => encrypt($row->id)]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>';
                // }
                return $btn;
            })
            ->addColumn('name', function ($row)
            {
                return $row->name;
            })
            ->addColumn('type', function ($row)
            {
                return $row->type;
            })
            ->addColumn('color', function ($row)
            {
               return '<span class="dot" style="background-color :'.$row->color.'"></span>';
            })
            ->addColumn('enabled', function ($row)
            {
                if ($row->enabled == '1')
                {
                    $btn = '<span class="badge badge-pill badge-success">'.trans('Enabled').'</span>';
                } else {
                    $btn = '<span class="badge badge-pill badge-danger">'.trans('Disabled').'</span>';
                }
                return $btn;
            })
            ->rawColumns(['action','name','type','color','enabled'])
            ->make(true);
    }

     /**
     * Display a listing of the loan resource
     *
     * @access public
     * @return mixed
     * @throws \Exception
     */
    public function taxList()
    {
        $tax = Tax::where('company_id', Session::get('company_id'))->orderBy('created_at', 'DESC')->get();

        return datatables()
            ->of($tax)
            ->addIndexColumn()
            ->addColumn('action', function($row)
            {
                $btn = '';
                // if (Auth::user()->hasPermissionTo('loan-edit'))
                // {

                    $btn .= '<a href="'.route('tax.edit',['tax' => encrypt($row->id)]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp';
                // }
                // if (Auth::user()->hasPermissionTo('loan-delete'))
                // {
                    $btn .= '<a href="#" data-href="'.route('tax.delete',['id' => encrypt($row->id)]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>';
                // }
                return $btn;
            })

            ->addColumn('name', function ($row)
            {
                return $row->name;
            })
            ->addColumn('rate', function ($row)
            {
                return $row->rate;
            })

            ->addColumn('type', function ($row)
            {
                if ($row->type == 'inclusive')
                {
                    $btn = '<span class="badge badge-pill badge-primary">'.trans('Inclusive').'</span>';
                } elseif ($row->type == 'exclusive') {
                    $btn = '<span class="badge badge-pill badge-warning">'.trans('Exclusive').'</span>';
                } else {
                    $btn = '<span class="badge badge-pill badge-secondary">'.trans('Normal').'</span>';
                }
                return $btn;
            })

            ->addColumn('enabled', function ($row)
            {
                if ($row->enabled == '1')
                {
                    $btn = '<span class="badge badge-pill badge-success">'.trans('Enabled').'</span>';
                } else {
                    $btn = '<span class="badge badge-pill badge-danger">'.trans('Disabled').'</span>';
                }
                return $btn;
            })
            ->rawColumns(['action','name','rate','type','enabled'])
            ->make(true);
    }

    public function AccountList()
    {
        $currencyData = Currency::where('company_id', Session::get('company_id'))->where('enabled','1')->select('symbol','symbol_first','thousands_separator','decimal_mark','precision')->first();
        $account = Account::where('company_id', Session::get('company_id'))->orderBy('created_at', 'DESC')->get();
        $account->makeHidden(['company_id','created_at']);
        return datatables()
            ->of($account)
            ->addIndexColumn()
            ->addColumn('action', function($row)
            {
                $btn = '';
                    $btn .= '<a href="'.route('account.edit',['account' => encrypt($row->id)]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp';
                    $btn .= '<a href="#" data-href="'.route('account.delete',['id' => encrypt($row->id)]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>';
                return $btn;
            })
            ->addColumn('name', function ($row)
            {
                return $row->name;
            })
            ->addColumn('number', function ($row)
            {
                return $row->number;
            })
            ->addColumn('balance', function ($row) use($currencyData)
            {
                $balance = $row->opening_balance;
                if($currencyData != null) {
                    if($currencyData->symbol_first == 1) {
                        return $currencyData->symbol." ".number_format($balance,$currencyData->precision,$currencyData->decimal_mark,$currencyData->thousands_separator);
                    } else {
                        return number_format($balance,$currencyData->precision,$currencyData->decimal_mark,$currencyData->thousands_separator)." ".$currencyData->symbol;
                    }
                } else {
                    return number_format($balance);
                }

            })
            ->addColumn('enabled', function ($row)
            {
                if ($row->enabled == '1')
                {
                    $btn = '<span class="badge badge-pill badge-success">'.trans('Enabled').'</span>';
                } else {
                    $btn = '<span class="badge badge-pill badge-danger">'.trans('Disabled').'</span>';
                }
                return $btn;
            })
            ->rawColumns(['name','number','balance','enabled','action'])
            ->make(true);
    }

    public function TransactionsList()
    {
        $accounts = Account::where('enabled','1')->where('company_id', Session::get('company_id'))->pluck('name', 'id');
        $type = null;
        $type_cats = empty($type) ? ['income', 'expense'] : $type;

        $paymentTransactions = [];
        if ($type != 'income') {
            $payments = BankingPayment::orderBy('id', 'DESC')->where('company_id', Session::get('company_id'))->get();
            $paymentTransactions = $this->addTransactions($payments, "Expense");
        }

        $revenueTransactions = [];
        if ($type != 'expense') {
            $revenues = Revenue::orderBy('id', 'DESC')->where('company_id', Session::get('company_id'))->get();
            $revenueTransactions = $this->addTransactions($revenues, "Income");
        }

        $myTransactions = array_merge($paymentTransactions,$revenueTransactions);

        return datatables()
            ->of($myTransactions)
            ->addIndexColumn()
            ->addColumn('date', function ($row)
            {
                return $row['paid_at'];
            })
            ->addColumn('account_name', function ($row)
            {
                return $row['account_name'];
            })
            ->addColumn('type', function ($row)
            {
                return $row['type'];
            })
            ->addColumn('category', function ($row)
            {
                return $row['category_name'];
            })
            ->addColumn('description', function ($row)
            {
                return $row['description'];
            })
            ->addColumn('amount', function ($row)
            {
                return $row['amount'];
            })
            ->rawColumns(['date','account_name','type','category','description','amount'])
            ->make(true);
    }

    protected function addTransactions($items, $type)
    {
        $transactions = [];
        foreach ($items as $item) {
            $category_name = $item->category_id;
            $transactions[] = [
                'paid_at'           => date("d M Y", strtotime($item->paid_at)),
                'account_name'      => $item->account->name,
                'type'              => $type,
                'description'       => $item->description,
                'amount'            => $item->amount,
                'currency_code'     => $item->currency_code,
                'category_name'     => $category_name,
            ];
        }
        return $transactions;
    }

    public function TransfersList()
    {
        $items = Transfer::with(['payment', 'payment.account', 'revenue', 'revenue.account'])->get();

        return datatables()
            ->of($items)
            ->addIndexColumn()
            ->addColumn('date', function ($row)
            {
                return date("d M Y h:ia", strtotime($row->payment->paid_at));
            })
            ->addColumn('from_account', function ($row)
            {
                return $row->payment->account->name;
            })
            ->addColumn('to_account', function ($row)
            {
                return $row->revenue->account->name;
            })
            ->addColumn('amount', function ($row)
            {
                return $row->payment->amount;
            })
            ->addColumn('action', function($row)
            {
                $btn = '';
                    $btn .= '<a href="'.route('transfers.edit',['transfer' => encrypt($row->id)]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp';
                    $btn .= '<a href="#" data-href="'.route('transfers.delete',['id' => encrypt($row->id)]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>';
                return $btn;
            })
            ->rawColumns(['date','from_account','to_account','amount','action'])
            ->make(true);
    }

    /**
     * Display a listing of the loan resource
     *
     * @access public
     * @return mixed
     * @throws \Exception
     */
    public function CustomerList()
    {
        $data = Customer::with('price:id,name')->with('cgroups:id,name')->where('company_id', Session::get('company_id'))->orderBy('created_at', 'DESC')->get();

        $data->makeHidden(['company_id','created_at','updated_at','id','cgroups','cgroups_id','price_group_id']);

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row)
            {
                $btn = '';
                // if (Auth::user()->hasPermissionTo('loan-edit'))
                // {
                    $btn .= '<a href="'.route('customer.edit',['customer' => encrypt($row->id)]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp';
                // }
                // if (Auth::user()->hasPermissionTo('loan-delete'))
                // {
                    $btn .= '<a href="#" data-href="'.route('customer.delete',['id' => encrypt($row->id)]).'" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>';
                // }
                return $btn;
            })
            ->addColumn('name', function ($row)
            {
                return $row->name;
            })
            ->addColumn('price_group', function ($row)
            {
                if($row->price()->exists()){
                    return $row->price->percent;
                } else {
                    return null;
                }
                // $price_name = Price::find($row->price_group_id);
                // return  $price_name->name;
            })
            ->addColumn('customer_group', function ($row)
            {
                if($row->cgroups()->exists()){
                    return $row->cgroups->name;
                } else {
                    return null;
                }
                // return $row->cgroups['name'];
            })
            ->rawColumns(['action','name','price_group','zip','vat','country','email','phone','city','state','address','remarks','price','customer_group','company_name'])
            ->make(true);

    }

}
