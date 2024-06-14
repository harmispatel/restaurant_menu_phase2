<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show Login Form
     */
    public function showLogin()
    {
        return view('auth.login');
    }


    /**
     * Authenticate the User
     *
     * @param Request $request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $input = $request->except('_token');

        if (Auth::attempt($input)){
            if (Auth::user()->user_type == 1){
                $username = Auth::user()->firstname." ".Auth::user()->lastname;
                return redirect()->route('admin.dashboard')->with('success', 'Welcome '.$username);
            }else{
                $username = Auth::user()->firstname." ".Auth::user()->lastname;
                $is_active = Auth::user()->status ?? "";
                $end_date = Auth::user()->hasOneSubscription->end_date ?? "";
                $end_date = Carbon::now()->diffInDays($end_date, false);

                if($end_date > 0){
                    if($is_active == 1){
                        return redirect()->route('client.dashboard')->with('success', 'Welcome '.$username);                        
                    }else{
                        Auth::logout();
                        return redirect()->route('login')->with('error', 'Your Account has been Deactivated!');
                    }
                }else{
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Your Pack has been Expired!');
                }        
            }
        }

        return back()->with('error', 'Please Enter Valid Email & Password');
    }


    /**
     * Logout the User
     */
    public function logout()
    {
        session()->forget('lang_code');
        session()->forget('locale');
        session()->save();
        Auth::logout();
        return redirect()->route('login');
    }
}
