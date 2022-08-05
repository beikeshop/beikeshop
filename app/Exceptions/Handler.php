<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\RegisterErrorViewPaths;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
}
