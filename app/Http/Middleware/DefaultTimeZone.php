<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class DefaultTimeZone
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
        $user = Auth::user() ?? [];
        $shop_id = (isset($user->hasOneShop['shop_id'])) ? $user->hasOneShop['shop_id'] : '';
        
        if(!empty($shop_id)){
            $client_settings = getClientSettings($shop_id);
            if(isset($client_settings['default_timezone']) && !empty($client_settings['default_timezone'])){
                date_default_timezone_set($client_settings['default_timezone']);
            }else{
                date_default_timezone_set('Europe/Athens');
            }
        }else{
            date_default_timezone_set('Europe/Athens');
        }
        return $next($request);
    }
}
