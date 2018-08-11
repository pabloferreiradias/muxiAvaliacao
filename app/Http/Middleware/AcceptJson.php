<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\HeaderBag;

class AcceptJson
{
    public function handle($request, Closure $next)
    {
        $request->server->set('HTTP_ACCEPT', 'application/json');
        $request->headers = new HeaderBag($request->server->getHeaders());

        return $next($request);
    }
}
