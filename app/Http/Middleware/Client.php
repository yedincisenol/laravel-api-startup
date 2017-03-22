<?php

namespace App\Http\Middleware;

use Closure;
use Laravel\Passport\Client as ClientModel;

class Client
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $client = ClientModel::where('id', $request->header('client-id'))
            ->where('secret', $request->header('client-secret'))
            ->first();

        if(!$client)
        {
            return response([
                'message'=> 'Unauthenticated Client',
                'status_code' => 401
            ], 401);
        }

        return $next($request);
    }
}