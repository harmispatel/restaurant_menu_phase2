<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class isClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user()->user_type == 2){
            // $is_active = Auth::user()->status ?? "";
            // $end_date = Auth::user()->hasOneSubscription->end_date ?? "";
            // $end_date = Carbon::now()->diffInDays($end_date, false);

            // if($end_date > 0){
            //     if($is_active == 0){
            //         Auth::logout();
            //         return redirect()->route('login')->with('error', 'Your Account has been Deactivated!');                   
            //     }else{
            //         return $next($request);
            //     }
            // }else{
            //     Auth::logout();
            //     return redirect()->route('login')->with('error', 'Your Pack has been Expired!');
            // }    
            return $next($request);               
        }else{
            return redirect('admin/dashboard')->with('error',"You don't have Client Access.");
        }
    }
}
