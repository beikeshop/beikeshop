<?php
/**
 * SettingRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-30 16:36:40
 * @modified   2022-06-30 16:36:40
 */

namespace Beike\Repositories;

use Carbon\Carbon;
use Beike\Models\Setting;

class SettingRepo
{
    /**
     * 按照类型分组获取设置
     */
    public static function getGroupedSettings(): array
    {
        $settings = Setting::all(['type', 'space', 'name', 'value', 'json']);

        $result = [];
        foreach ($settings as $setting) {
            $type = $setting->type;
            if (!in_array($type, Setting::TYPES)) {
                continue;
            }

            $space = $setting->space;
            $name = $setting->name;
            $value = $setting->value;
            if ($setting->json) {
                $result[$type][$space][$name] = json_decode($value, true);
            } else {
                $result[$type][$space][$name] = $value;
            }
        }
        return $result;
    }


    /**
     * 获取插件默认字段
     *
     * @return array
     */
    public static function getPluginStatusColumn(): array
    {
        return [
            'name' => 'status',
            'label' => trans('common.whether_open'),
            'type' => 'bool',
            'required' => true,
        ];
    }


    /**
     * 获取单个插件所有字段
     * @param $pluginCode
     * @return mixed
     */
    public static function getPluginColumns($pluginCode)
    {
        return Setting::query()
            ->where('type', 'plugin')
            ->where('space', $pluginCode)
            ->get()
            ->keyBy('name');
    }


    /**
     * 获取单个插件状态
     *
     * @param $pluginCode
     * @return bool
     */
    public static function getPluginStatus($pluginCode): bool
    {
        $status = plugin_setting("{$pluginCode}.status");
        return (bool)$status;
    }


    /**
     * 批量更新设置
     *
     * @param $type
     * @param $code
     * @param $fields
     */
    public static function update($type, $code, $fields)
    {
        $columns = array_keys($fields);
        Setting::query()
            ->where('type', $type)
            ->where('space', $code)
            ->whereIn('name', $columns)
            ->delete();

        $rows = [];
        foreach ($fields as $name => $value) {
            if (in_array($name, ['_method', '_token'])) {
                continue;
            }
            $rows[] = [
                'type' => $type,
                'space' => $code,
                'name' => $name,
                'value' => (string)$value,
                'json' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        Setting::query()->insert($rows);
    }


    /**
     * 创建或更新单条记录
     *
     * @param $data
     * @throws \Throwable
     */
    public static function storeValue($name, $value, $space = 'base', $type = 'system')
    {
        if (in_array($name, ['_method', '_token'])) {
            return;
        }

        $setting = Setting::query()
            ->where('type', $type)
            ->where('space', $space)
            ->where('name', $name)
            ->first();

        $settingData = [
            'type' => $type,
            'space' => $space,
            'name' => $name,
            'value' => is_array($value) ? json_encode($value) : $value,
            'json' => is_array($value),
        ];

        if (empty($setting)) {
            $setting = new Setting($settingData);
            $setting->saveOrFail();
        } else {
            $setting->update($settingData);
        }
    }
}
