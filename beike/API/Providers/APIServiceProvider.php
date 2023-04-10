<?php
/**
 * APIServiceProvider.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-04-11 16:08:04
 * @modified   2023-04-11 16:08:04
 */

namespace Beike\API\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class APIServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (is_installer()) {
            return;
        }
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->registerGuard();
    }

    /**
     * 注册后台用户 guard
     */
    protected function registerGuard()
    {
        Config::set('auth.guards.api_customer', [
            'driver'   => 'jwt',
            'provider' => 'shop_customer',
        ]);
    }
}
