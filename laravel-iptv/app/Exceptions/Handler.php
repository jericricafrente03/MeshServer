<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Str;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            // Redirect to login if CSRF token mismatch occurs
            return redirect('/login')->with('message', 'Your session has expired. Please log in again.');
        }

        if( $request->isMethod('get') )
        {
            if($exception->getMessage() == 'Unauthenticated.')
            {
                return parent::render($request, $exception);
            }

            if (method_exists($exception, 'getStatusCode')) {
                if ($exception->getStatusCode() === 403) {
                    // if($request->route()->getActionName() == '\App\Http\Controllers\DashboardController@index') {
                    //     return redirect('/lost');
                    // };
                    return response()->view('/errors/403/index', [], 403);
                }

                if ($exception->getStatusCode() === 404) {
                    return redirect('/admin');
                    // return response()->view('/errors/404/index', [], 404);
                }
            }
        }

        return parent::render($request, $exception);
    }

    //sanctum handler unauth return
    public function unauthenticated($request, AuthenticationException $exception)
    {
        // Check if the request is an API request or Sanctum-specific
        if ($request->expectsJson() || Str::contains($request->header('Authorization'), 'Bearer')) {
            return response()->json([
                'data' => [],
                'result' => 'failed',
                'reason' => 'Unauthenticated.'
            ], 401);
        }

        // For web requests, redirect to login page
        return redirect()->guest(route('login'));
    }
}
