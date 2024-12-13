<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\Controller;
use Redirect;
use Purifier;

class Handler extends ExceptionHandler {

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception) {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception) {
        $nomeib = ConfiguracaoController::getAll()->nomeib;
        if ($exception instanceof \Illuminate\Http\Exceptions\PostTooLargeException) {
            return response(View('pages.erro')->with('msgErro', 'Tamanho do arquivo excedeu o máximo permitido'), 413);
        }
        
        if($this->isHttpException($exception) && $exception->getStatusCode() == 404){
            $exploded = explode("/", strip_tags(Purifier::clean($request->getRequestUri())));
            if($exploded[1] == "%C3%A7") $exploded[1] = "ç";
            $board = (new Controller)->boardExiste(html_entity_decode($exploded[1]));
            if($board){
                $thread = '';
                if(count($exploded) > 2)
                {
                    $thread = $exploded[2];
                }
                return Redirect::to('/boards/' . $board->sigla . ($thread ? '/'. $thread :''));
            }
            return response(View('pages.erro')->with('msgErro', 'Página inexistente'), 404);
        }
        
        if($this->isHttpException($exception) && $exception->getStatusCode() == 400){
            return response(View('pages.erro')->with('msgErro', 'Requisição inválida'), 400);
        }
        
        if($this->isHttpException($exception) && $exception->getStatusCode() == 500){
            return response(View('pages.erro')->with('msgErro', 'Erro interno do servidor'), 500);
        }
        
        if($this->isHttpException($exception) && $exception->getStatusCode() == 429){
            return response(View('pages.erro')->with('msgErro', 'Muitas requisições por minuto. Vá com calma aí, amigão'), 429);
        }
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception) {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(url('/'));
    }

    protected function whoopsHandler()
    {
        try {
            return app(\Whoops\Handler\HandlerInterface::class);
        } catch (\Illuminate\Contracts\Container\BindingResolutionException $e) {
            return parent::whoopsHandler();
        }
    }

}
