<?php

namespace App\Exceptions;

use Arr;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use ReflectionClass;
use Throwable;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Convert the given exception to an array.
     *
     * @param  \Throwable  $e
     * @return array
     */
    protected function convertExceptionToArray(Throwable $e)
    {
        $config = $this->container->make('config');
        $isDebug = $config && $config->get('app.debug');
        $code = $this->isHttpException($e) ? (new ReflectionClass($e))->getShortName() : 'UnknownError';
        $error = [
            'message' => $isDebug || $this->isHttpException($e) ? $e->getMessage() : 'Server Error',
            'code' => $code,
        ];
     
        if ($isDebug) {
            $error['meta'] = [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => collect($e->getTrace())->map(function ($trace) {
                    return Arr::except($trace, ['args']);
                })->all(),
            ];
        }
        
        return [
            'data' => null,
            'errors' => [ $error ],
        ];
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {
            return $this->prepareJsonResponse($request, $e);
        }

        return parent::render($request, $e);
    }
        
    /**
     * Output validation exceptions from Form Requests as json.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }

        $errors = collect($e->validator->errors()->toArray())
            ->flatten()
            ->map(function (string $message) {
                return [
                    "code" => "ValidationError",
                    "message" => $message,
                ];
            })
            ->values()
            ->toArray();

        return response()->json([
            'data' => null,
            'errors' => $errors,
        ], 400);
    }
}
