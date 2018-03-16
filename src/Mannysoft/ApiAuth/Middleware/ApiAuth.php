<?php 

namespace Mannysoft\ApiAuth\Middleware;

use Closure;

class ApiAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user() == null) {
            // return response()->json(auth()->user());
            return response(['status' => 'failed', 'message' => 'Unauthorized.'], 401);
        }
        
        return $next($request);
    }
}
