<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Session\TokenMismatchException;
use App\Exceptions\RestApiValidationErrorException;
use App\Exceptions\ForbiddenPermissionAccessException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->except('_token'))
                             ->with('swal_error', 'Sorry, Your session is ended Please try again!');
        } elseif ($exception instanceof ForbiddenPermissionAccessException) {
            return redirect()->route($exception->getPermissionScope() . '.dashboard')
                             ->with('swal_error', 'You don\'t have permission to ' . $exception->getPermissionName());
        } elseif ($exception instanceof RestApiValidationErrorException) {
            $errorData = $exception->getErrors();

            return response()->json($errorData, 422);
        } elseif ($exception instanceof ModelNotFoundException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Data not found!'
                ], 404);
            }
            abort(404);
        }

        return parent::render($request, $exception);
    }
}
