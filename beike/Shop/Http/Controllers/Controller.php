<?php

namespace Beike\Shop\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends \App\Http\Controllers\Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

}
