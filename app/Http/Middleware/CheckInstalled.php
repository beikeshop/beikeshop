<?php
/**
 * CheckInstalled.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2023-04-07 15:46:13
 * @modified   2023-04-07 15:46:13
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckInstalled
{
    public function handle(Request $request, Closure $next)
    {
        if (installed()) {
            exit('Already installed');
        }

        return $next($request);
    }
}
