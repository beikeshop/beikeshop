<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\View;

class RegisterAdminErrorViewPaths
{
    /**
     * Register the error view paths.
     *
     * @return void
     */
    public function __invoke()
    {
        if (is_admin()) {
            $viewPaths[] = base_path('resources/beike/admin/views');
            View::replaceNamespace('errors', collect($viewPaths)->map(function ($path) {
                return "{$path}/errors";
            })->all());
        }
    }
}
