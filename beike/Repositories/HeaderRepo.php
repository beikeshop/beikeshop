<?php
/**
 * FooterRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-11 18:16:06
 * @modified   2022-08-11 18:16:06
 */

namespace Beike\Repositories;

class HeaderRepo
{
    /**
     * 处理页头
     *
     * @return array|mixed
     */
    public static function handleHeaderData($headerSetting = [])
    {
        if (empty($headerSetting)) {
            $headerSetting = system_setting('base.header_setting');
        }

        if (isset($headerSetting['header_ads'])) {
            $headerSetting['header_ads'] = [
                'bg_color' => $headerSetting['header_ads']['bg_color'] ?? '#222222',
                'color' => $headerSetting['header_ads']['color'] ?? '#ffffff',
                'active' => $headerSetting['header_ads']['active'] ?? 0,
                'items'    => collect($headerSetting['header_ads']['items'])->map(function ($item) {
                    return [
                        'title' => $item['title'][locale()] ?? '',
                        'link' => handle_link($item['link'])['link'] ?? '',
                    ];
                }),
            ];
        }

        return $headerSetting;
    }
}
