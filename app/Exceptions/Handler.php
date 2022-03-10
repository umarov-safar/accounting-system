<?php

namespace App\Exceptions;

use Arr;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use ReflectionClass;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
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
            if ($e instanceof ValidationException) {
                return $this->convertValidationExceptionToResponse($e, $request);
            }

            $e = $this->prepareException($e);

            return new JsonResponse(
                $this->convertExceptionToArray($e),
                $this->getExceptionStatusCode($e),
                $e instanceof HttpExceptionInterface ? $e->getHeaders() : [],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );
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

        return response()->json($this->formatErrorPayload($errors), 400);
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
        $isDebug = $config->get('app.debug');

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

        return $this->formatErrorPayload([ $error ]);
    }

    protected function getExceptionStatusCode(Throwable $exception): int
    {
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        if ($exception instanceof ModelNotFoundException) {
            return 404;
        }

        return 500;
    }

    private function formatErrorPayload(array $errorData): array
    {
        return [
            'data'   => null,
            'errors' => $errorData,
        ];
    }
}
