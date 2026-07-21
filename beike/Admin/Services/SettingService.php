<?php

/**
 * SettingService.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2023-12-13 12:12:57
 * @modified   2023-12-13 12:12:57
 */

namespace Beike\Admin\Services;

use Beike\Repositories\CurrencyRepo;
use Beike\Repositories\SettingRepo;
use Exception;

class SettingService
{
    /**
     * 保存系统设置
     * @param $settings
     * @return void
     * @throws \Throwable
     */
    public static function storeSettings($settings)
    {
        if (isset($settings['currency'])) {
            $currency = CurrencyRepo::findByCode($settings['currency']);
            if ($currency->value != 1) {
                throw new Exception('默认货币汇率必须为1 <a href="' . admin_route('currencies.index') . '">前往设置</a>');
            }
        }

        // 验证 admin_name 字段，仅支持小写字母和数字
        if (isset($settings['admin_name'])) {
            $adminName = $settings['admin_name'];
            if (! preg_match('/^[a-z0-9]+$/', $adminName)) {
                throw new Exception(trans('admin/setting.admin_name_error'));
            }
        }

        foreach ($settings as $key => $value) {
            SettingRepo::storeValue($key, $value);
        }
    }
}
