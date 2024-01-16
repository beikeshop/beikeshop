<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

if (! function_exists('isActive')) {
    /**
     * Set the active class to the current opened menu.
     *
     * @param string|array $route
     * @param string       $className
     * @return string
     */
    function isActive($route, $className = 'active')
    {
        if (is_array($route)) {
            return in_array(Route::currentRouteName(), $route) ? $className : '';
        }
        if (Route::currentRouteName() == $route) {
            return $className;
        }
        if (strpos(URL::current(), $route)) {
            return $className;
        }

        return '';
    }
}
