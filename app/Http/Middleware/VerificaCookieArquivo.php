<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Controller;
use App\Helpers\Funcoes;

class VerificaCookieArquivo
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
        Funcoes::consolelog('VerificaCookieArquivo::handle');
        if ((new Controller)->temBiscoito()) {
            return $next($request);
        }

        Funcoes::consolelog('VerificaCookieArquivo::handle redirecionando para o cancro:');
        return redirect('https://www.facebook.com');
    }
    
}
