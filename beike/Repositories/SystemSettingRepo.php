<?php
/**
 * SystemSettingRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-25 20:26:15
 * @modified   2022-07-25 20:26:15
 */

namespace Beike\Repositories;

class SystemSettingRepo
{
    /**
     * 获取系统设置
     */
    public static function getList(): array
    {
        return [
            [
                'name' => 'country_id',
                'label' => '默认国家',
                'type' => 'select',
                'required' => true,
                'options' => [
                    ['value' => '1', 'label' => '中国'],
                    ['value' => '2', 'label' => '美国']
                ],
                'value' => old('country_id', system_setting('base.country_id', '1')),
                'description' => '默认国家选择',
            ],
            [
                'name' => 'locale',
                'label' => '默认语言',
                'type' => 'select',
                'required' => true,
                'options' => [
                    ['value' => 'zh_cn', 'label' => '简体中文'],
                    ['value' => 'en', 'label' => '英文']
                ],
                'value' => old('locale', system_setting('base.locale', 'zh_cn')),
                'description' => '密钥(Secret key)',
            ],
            [
                'name' => 'admin_name',
                'label' => '后台目录',
                'type' => 'string',
                'required' => true,
                'value' => old('admin_name', system_setting('base.admin_name', 'admin')),
                'description' => '管理后台目录,默认为admin',
            ],
            [
                'name' => 'theme',
                'label' => '主题模板',
                'type' => 'select',
                'options' => [
                    ['value' => 'default', 'label' => '默认主题'],
                    ['value' => 'black', 'label' => '黑色主题']
                ],
                'value' => old('theme', system_setting('base.theme', 'default')),
                'required' => true,
                'description' => '主题模板选择',
            ]
        ];
    }
}
