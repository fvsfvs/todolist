<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \Illuminate\Auth\AuthenticationException;
use \Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    
    /**
     * register
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof ValidationException) {
                return response()->json(['message' => 'Bad request.'], 400);
            }
            else if($e instanceof NotFoundHttpException) {
                return response()->json(['message' => 'Not found.'], 404);
            }
            else if($e instanceof AuthenticationException){
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            else if($e instanceof MethodNotAllowedHttpException){
                return response()->json(['message' => 'Method not allowed.'], 405);
            }
            else{
                return response()->json(['message' => 'Internal error.'], 500);
            }
        });
    }

}
