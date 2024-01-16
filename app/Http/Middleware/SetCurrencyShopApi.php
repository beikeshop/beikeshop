<?php
/**
 * SetCurrencyShopApi.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-25 16:40:19
 * @modified   2023-08-25 16:40:19
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetCurrencyShopApi
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $currency = $request->header('currency');
        if (empty($currency)) {
            $currency = $request->get('currency');
        }
        $currency = $currency ?? system_setting('base.currency');
        register('currency', $currency);

        return $next($request);
    }
}
