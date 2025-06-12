<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Exceptions\RegisterErrorViewPaths;
use Illuminate\Support\Arr;
use Throwable;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Convert the given exception to an array.
     *
     * @param \Throwable $e
     * @return array
     */
    protected function convertExceptionToArray(Throwable $e)
    {

        return config('app.debug') ? [
            'message'   => $e->getMessage(),
            'exception' => get_class($e),
            'file'      => $e->getFile(),
            'line'      => $e->getLine(),
            'trace'     => collect($e->getTrace())->map(fn ($trace) => Arr::except($trace, ['args']))->all(),
        ] : [
            'message' => $this->isHttpException($e) ? $e->getMessage() : 'Server Error',
        ];
    }

    /**
     * 自定义错误信息页面, 前台与后台不同, 需要分开定义
     */
    protected function registerErrorViewPaths()
    {
        if (is_admin()) {
            (new RegisterAdminErrorViewPaths())();
        } else {
            (new RegisterErrorViewPaths())();
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            // 处理权限验证异常
            if ($exception instanceof AuthorizationException) {
                return response()->json([
                    'code'    => 403,
                    'message' => trans('admin/common.no_permission'),
                ], 403);
            }

            // 处理表单验证异常
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'code'    => 422,
                    'message' => $exception->getMessage(),
                    'errors'  => $exception->errors(),
                ], 422);
            }

            // CSRF Token 失效
            if ($exception instanceof TokenMismatchException) {
                return response()->json([
                    'message' => __('auth.token_expired'),
                ], 419);
            }

            // 其他 Ajax 异常
            $statusCode = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;
            $message = $exception->getMessage();

            if (empty($message) || $message === 'Server Error') {
                $message = '服务器错误，请联系管理员';
            }

            return response()->json([
                'code' => $statusCode,
                'message' => $message,
            ], $statusCode);
        }

        return parent::render($request, $exception);
    }
}
