<?php

namespace App\Http\Middleware;

use Closure;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('locale')) {
            if (in_array(strtolower($request->locale), ['en', 'np'])) {
                session()->put('locale', $request->locale);
            } else {
                session()->put('locale', 'en');
            }
        }
        if(session()->has('locale')) {
            app()->setLocale(session('locale'));
        }
        return $next($request);    }
}
