<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Administrator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //createe a custom config file <<< which we did in config('lms.administrators')

        if(auth()->check()){ // if user authenticated
            if(auth()->user()->isAdmin()){ //.. then, check if he is an admin
                return $next($request);
            }
        }
        session()->flash('error', 'You are not autherised to perform this action');
        return redirect('/');
    }
}
