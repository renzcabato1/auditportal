<?php

namespace App\Http\Middleware;

use Closure;

class ForAudit
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
      
        if(auth()->user()->team_id() != null)
        {
            $roles = auth()->user()->team_id()->role;
            $roles_array = json_decode($roles);
        }
        if((in_array(5,$roles_array)) || (in_array(1,$roles_array)) )
        {
            return $next($request);
        }
        else
        {
            return redirect('/home');
        }
        
    }
}
