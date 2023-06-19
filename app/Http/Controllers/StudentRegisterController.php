<?php

namespace App\Http\Controllers;

use App\Models\StudentRegister;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class StudentRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        $input = array();
        $input['name'] = $request['name'];
        $input['email'] = $request['email'];
        $input['password'] = bcrypt($request['password']);
        $input['status'] = "1";

        $user = User::create($input);

        $roles = "Student";
        $companies = "1";

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

        session()->flash('flash_notification.success', 'Congratulations, Please verify your email address !');


        return redirect()->route('login');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentRegister  $studentRegister
     * @return \Illuminate\Http\Response
     */
    public function show(StudentRegister $studentRegister)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentRegister  $studentRegister
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentRegister $studentRegister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentRegister  $studentRegister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentRegister $studentRegister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentRegister  $studentRegister
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentRegister $studentRegister)
    {
        //
    }
}
