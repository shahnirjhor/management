<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

/**
 * Class UserController
 * @package App\Http\Controllers
 * @category Controller
 */
class UserController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:user-read|user-create|user-update|user-delete', ['only' => ['index']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-update', ['only' => ['editUser','updateUser']]);
        $this->middleware('permission:user-delete', ['only' => ['destroyUser']]);
        $this->middleware('permission:user-export', ['only' => ['doExport']]);

        $this->middleware('permission:student-read|student-create|student-update|student-delete', ['only' => ['studentIndex']]);
        $this->middleware('permission:student-create', ['only' => ['createStudent','storeStudent']]);
        $this->middleware('permission:student-update', ['only' => ['editStudent','updateStudent']]);
        $this->middleware('permission:student-delete', ['only' => ['destroyUser']]);
        $this->middleware('permission:student-export', ['only' => ['doExportStudent']]);
    }

    /**
     * Display a listing of the resource
     *
     * @access public
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->export)
            return $this->doExport($request);
        $users = $this->filter($request)->paginate(10)->withQueryString();
        return view('users.index',compact('users'));
    }

    public function studentIndex(Request $request)
    {
        $users = $this->studentFilter($request)->paginate(10)->withQueryString();
        return view('users.student_index',compact('users'));
    }

    private function studentFilter(Request $request)
    {
        $query = User::role('Student')->orderBy('id','DESC');

        if ($request->id)
            $query->where('id', $request->id);

        if ($request->name)
            $query->where('name', 'like', $request->name.'%');

        if ($request->email)
            $query->where('email', 'like', $request->email.'%');

        return $query;
    }

    private function filter(Request $request)
    {
        $query = User::notRole('Student')->orderBy('id','DESC');

        if ($request->id)
            $query->where('id', $request->id);

        if ($request->name)
            $query->where('name', 'like', $request->name.'%');

        if ($request->email)
            $query->where('email', 'like', $request->email.'%');

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
        return Excel::download(new UsersExport($request), 'users.xlsx');
    }

    public function readItemsOutOfStock(User $user)
    {
        foreach ($user->unreadNotifications as $notification) {
            if ($notification->getAttribute('type') != 'App\Notifications\ItemReminder') {
                continue;
            } elseif($notification->getAttribute('type') != 'App\Notifications\Item')
            {
                continue;
            } else {
                $notification->markAsRead();
            }
        }

        return redirect()->route('item.index');
    }

    /**
     * Show the form for creating a new resource
     *
     * @access public
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $staffRoles = Role::where('name','!=', 'Student')->where('role_for', '1')->pluck('name','name')->all();
        // dd($staffRoles);
        $userRoles = Role::where('role_for', '0')->pluck('name','name')->all();
        $companies = auth()->user()->companies()->get();
        foreach ($companies as $company) {
            $company->setSettings();
        }
        return view('users.create',compact('staffRoles','userRoles','companies'));
    }

    public function createStudent()
    {
        $staffRoles = Role::where('role_for', '1')->pluck('name','name')->all();
        $companies = auth()->user()->companies()->get();
        foreach ($companies as $company) {
            $company->setSettings();
        }
        return view('users.student_create',compact('staffRoles','companies'));
    }

    /**
     * Store a newly created resource in storage
     *
     * @access public
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:password_confirmation',
            'status' => 'required',
            'role_for' => 'required'
        ]);

        $logoUrl = "";
        if($request->hasFile('photo'))
        {

            $this->validate($request, [
                'photo' => 'image|mimes:png,jpg,jpeg'
            ]);

            $logo = $request->photo;
            $logoNewName = time().$logo->getClientOriginalName();
            $logo->move('lara/employee',$logoNewName);
            $logoUrl = 'lara/employee/'.$logoNewName;
        }

        if($request->role_for == "1") //staff
        {
            $roles = $request->staff_roles;
            $companies = $request->staff_company;
        }

        if($request->role_for == "0") //user
        {
            $roles = $request->user_roles;
            $companies = $request->user_company; //array
        }


        $input = array();
        $input['name'] = $request->name;
        $input['email'] = $request->email;
        $input['password'] = bcrypt($request->password);
        $input['phone'] = $request->phone;
        $input['address'] = $request->address;
        $input['status'] = $request->status;
        $input['photo'] = $logoUrl;
        $user = User::create($input);
        $user->assignRole($roles);
        if($request->role_for == "1") //staff
        {
            // Attach company
            $user->companies()->attach($companies);
        }
        if($request->role_for == "0") //user
        {
            if (isset($companies) && !empty($companies)) {
                foreach ($companies as $company){
                    $user->companies()->attach($company);
                }
            }
        }

        $token = Str::random(64);
        UserVerify::create([
            'user_id' => $user->id,
            'token' => $token
        ]);
        Mail::send('email.emailVerificationEmail', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Email Verification Mail');
        });

        return redirect()->route('users.index')->with('success', trans('User Created Successfully'));
    }

    public function storeStudent(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:password_confirmation',
            'status' => 'required',
            'role_for' => 'required'
        ]);

        $logoUrl = "";
        if($request->hasFile('photo'))
        {
            $this->validate($request, [
                'photo' => 'image|mimes:png,jpg,jpeg'
            ]);
            $logo = $request->photo;
            $logoNewName = time().$logo->getClientOriginalName();
            $logo->move('lara/student',$logoNewName);
            $logoUrl = 'lara/student/'.$logoNewName;
        }

        $roles = "Student";
        $companies = "1";

        $input = array();
        $input['name'] = $request->name;
        $input['email'] = $request->email;
        $input['password'] = bcrypt($request->password);
        $input['phone'] = $request->phone;
        $input['address'] = $request->address;
        $input['status'] = $request->status;
        $input['photo'] = $logoUrl;
        $user = User::create($input);
        $user->assignRole($roles);
        $user->companies()->attach($companies);


        $token = Str::random(64);
        UserVerify::create([
            'user_id' => $user->id,
            'token' => $token
        ]);
        Mail::send('email.emailVerificationEmail', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Email Verification Mail');
        });


        return redirect()->route('student.studentIndex')->with('success', trans('Student Created Successfully'));
    }

    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();

        $message = 'Sorry your email cannot be identified.';

        if(!is_null($verifyUser) ){
            $user = $verifyUser->user;

            if(!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Your e-mail is verified. You can now login.";
            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }
        }

      return redirect()->route('login')->with('message', $message);
    }

    /**
     * Store a newly created resource in storage
     *
     * @access public
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource
     *
     * @access public
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editUser($id)
    {
        // dd($id);
        $myUser = User::findOrFail($id);
        $roleName = $myUser->getRoleNames();
        $roleFor = Role::findByName($roleName['0']);

        $cId = array();
        $selectedCompanies = $myUser->companies()->select('id')->get();
        foreach ($selectedCompanies as $companies) {
            $cId[] = $companies->id;
        }
        $cIdStd = implode(",",$cId);

        $staffRoles = Role::where('name','!=', 'Student')->where('role_for', '1')->pluck('name','name')->all();
        $userRoles = Role::where('role_for', '0')->pluck('name','name')->all();
        $companies = auth()->user()->companies()->get();

        foreach ($companies as $company) {
            $company->setSettings();
        }
        // dd($myUser);
        return view('users.edit',compact('myUser','roleFor','staffRoles', 'userRoles','companies','cIdStd'));
    }

    public function editStudent($id)
    {
        $student = User::findOrFail($id);
        $roleName = $student->getRoleNames();
        $roleFor = Role::findByName($roleName['0']);

        $cId = array();
        $selectedCompanies = $student->companies()->select('id')->get();
        foreach ($selectedCompanies as $companies) {
            $cId[] = $companies->id;
        }
        $cIdStd = implode(",",$cId);

        $staffRoles = Role::where('role_for', '1')->pluck('name','name')->all();
        $companies = auth()->user()->companies()->get();

        foreach ($companies as $company) {
            $company->setSettings();
        }

        // dd($user);
        return view('users.studentEdit',compact('student','roleFor','staffRoles','companies','cIdStd'));
    }

    public function updateUser(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:password_confirmation',
            'status' => 'required',
            'role_for' => 'required'
        ]);
        $logoUrl = "";
        if($request->hasFile('photo'))
        {

            $this->validate($request, [
                'photo' => 'image|mimes:png,jpg,jpeg'
            ]);

            $logo = $request->photo;
            $logoNewName = time().$logo->getClientOriginalName();
            $logo->move('lara/employee',$logoNewName);
            $logoUrl = 'lara/employee/'.$logoNewName;
        }
        $user = User::findOrFail($id);
        $password = $user->password;
        if($request->role_for == "1") //staff
        {
            $roles = $request->staff_roles;
            $companies = $request->staff_company;
        }
        if($request->role_for == "0") //user
        {
            $roles = $request->user_roles;
            $companies = $request->user_company; //array
        }
        $input = array();
        $input['name'] = $request->name;
        $input['email'] = $request->email;
        if (!empty($request->password))
        {
            $input['password'] = bcrypt($input['password']);
        } else {
            $input['password'] = $password;
        }
        $input['phone'] = $request->phone;
        $input['address'] = $request->address;
        $input['status'] = $request->status;
        $input['photo'] = $logoUrl;
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$user->id)->delete();
        $userSelectedCompaniesStr = $request->user_selected_companies;
        $userSelectedCompaniesArray = explode(',',$userSelectedCompaniesStr);
        foreach ($userSelectedCompaniesArray as $company) {
            DB::table('user_companies')->where('user_id',$user->id)->where('company_id',$company)->delete();
        }
        if($request->role_for == "1") //staff
        {
            // Attach company
            $user->companies()->attach($companies);
        }
        if($request->role_for == "0") //user
        {
            if(!empty($companies))
            {
                foreach ($companies as $company)
                {
                    $user->companies()->attach($company);
                }
            }
        }
        $user->assignRole($roles);
        return redirect()->route('users.index')->with('success', trans('User Updated Successfully'));
    }

    public function updateStudent(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:password_confirmation',
            'status' => 'required',
            'role_for' => 'required'
        ]);
        $logoUrl = "";
        if($request->hasFile('photo'))
        {

            $this->validate($request, [
                'photo' => 'image|mimes:png,jpg,jpeg'
            ]);

            $logo = $request->photo;
            $logoNewName = time().$logo->getClientOriginalName();
            $logo->move('lara/student',$logoNewName);
            $logoUrl = 'lara/student/'.$logoNewName;
        }
        $student = User::findOrFail($id);
        $password = $student->password;
        $roles = "Student";
        $companies = "1";
        $input = array();
        $input['name'] = $request->name;
        $input['email'] = $request->email;
        if (!empty($request->password))
        {
            $input['password'] = bcrypt($input['password']);
        } else {
            $input['password'] = $password;
        }
        $input['phone'] = $request->phone;
        $input['address'] = $request->address;
        $input['status'] = $request->status;
        $input['photo'] = $logoUrl;
        $student->update($input);

        DB::table('model_has_roles')->where('model_id',$student->id)->delete();
        $userSelectedCompaniesStr = $request->user_selected_companies;
        $userSelectedCompaniesArray = explode(',',$userSelectedCompaniesStr);
        foreach ($userSelectedCompaniesArray as $company) {
            DB::table('user_companies')->where('user_id',$student->id)->where('company_id',$company)->delete();
        }
        if($request->role_for == "1") //staff
        {
            // Attach company
            $student->companies()->attach($companies);
        }

        $student->assignRole($roles);
        return redirect()->route('student.studentIndex')->with('success', trans('Student Updated Successfully'));

    }


    /**
     * Remove the specified resource from storage
     *
     * @param $id
     * @access public
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyUser(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($request->id);
            $user->delete();
            DB::table("model_has_roles")->where('model_id',$user->id)->delete();
            DB::table("user_companies")->where('user_id',$user->id)->delete();
            DB::commit();
            return redirect()->back()->with('success', trans('User Deleted Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function destroyStudent($id)
    {
        dd($id);
    }
}
