<?php
/**
 * Hook.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-04-21 16:10:33
 * @modified   2022-04-21 16:10:33
 */

namespace Beike\Plugin;

use Beike\Events\ConfigureRoutes;
use Illuminate\Support\Facades\Event;

class Hook
{
    public static function addMenuItem()
    {
    }

    public static function addRoute(callable $callback)
    {
        Event::listen(ConfigureRoutes::class, function ($event) use ($callback) {
            return call_user_func($callback, $event->router);
        });
    }
}
