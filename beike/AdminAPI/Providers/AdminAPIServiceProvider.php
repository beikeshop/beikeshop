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

namespace Beike\AdminAPI\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AdminAPIServiceProvider extends ServiceProvider
{
    public function boot(): void
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
    protected function registerGuard(): void
    {
        Config::set('auth.guards.api_customer', [
            'driver'   => 'jwt',
            'provider' => 'shop_customer',
        ]);
    }
}
