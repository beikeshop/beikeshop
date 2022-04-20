<?php
/**
 * SettingRepository.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-04-18 17:32:50
 * @modified   2022-04-18 17:32:50
 */

namespace App\Repositories;

class SettingRepository
{
    public function get($key)
    {
        $setting = \DB::table('settings')->where('name', $key)->first();

        if ($setting) {
            return $setting->value;
        }

        return null;
    }

    public function set($key, $value)
    {
        $setting = \DB::table('settings')->where('name', $key)->first();

        if ($setting) {
            \DB::table('settings')->where('name', $key)->update(['value' => $value]);
        } else {
            \DB::table('settings')->insert(['name' => $key, 'value' => $value]);
        }
    }
}
