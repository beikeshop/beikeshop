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

class FooterRepo
{
    /**
     * 处理页尾编辑器数据
     *
     * @return array|mixed
     */
    public static function handleFooterData($footerSetting = [])
    {
        if (empty($footerSetting)) {
            $footerSetting = system_setting('base.footer_setting');
        }

        $content         = $footerSetting['content'];
        $contentLinkKeys = ['link1', 'link2', 'link3'];
        foreach ($contentLinkKeys as $contentLinkKey) {
            $links = $content[$contentLinkKey]['links'];
            $links = collect($links)->map(function ($link) {
                return handle_link($link);
            })->toArray();
            $footerSetting['content'][$contentLinkKey]['links'] = $links;
        }

        return $footerSetting;
    }
}
