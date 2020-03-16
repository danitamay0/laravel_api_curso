<?php

namespace App\Http\Middleware;

use Closure;

class signatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $header='X-header')
    {
        #un postMiddleware funciona despues que se ejecuta el código y se dará respuesta
        $response=$next($request);
        $response->headers->set($header,config('app.name') );
        return $response;
    }
}
