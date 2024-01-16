<?php
/**
 * Bootstrap.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-09-04 16:04:23
 * @modified   2023-09-04 16:04:23
 */

namespace Plugin\Youdao;

use Plugin\Youdao\Services\YoudaoService;

class Bootstrap
{
    public function boot(): void
    {
        add_hook_filter('admin.service.translator', function ($data) {
            return YoudaoService::class;
        });
    }
}
