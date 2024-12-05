<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Funcoes;

class VerifyHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Funcoes::consolelog("VerifyHeaders::handle");
        if(!$request->server('HTTP_ACCEPT_ENCODING') || 
        !$request->server('HTTP_ACCEPT_LANGUAGE') || 
        !$request->server('HTTP_USER_AGENT') || 
           str_contains($request->server('HTTP_USER_AGENT'), 'curl'))
        {
            Funcoes::consolelog("VerifyHeaders::handle Requisição inválida, headers:\n");
            Funcoes::consolelog(getallheaders());
            Funcoes::consolelog(" Remote addr:");
            Funcoes::consolelog($_SERVER['REMOTE_ADDR']);
            Funcoes::consolelog(" to content:");
            Funcoes::consolelog($_SERVER['REQUEST_URI']);
            abort(400);
        }
        
        return $next($request);
    }
}
