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

use Beike\Repositories\FooterRepo;
use Beike\Repositories\LanguageRepo;
use Beike\Repositories\MenuRepo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

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
