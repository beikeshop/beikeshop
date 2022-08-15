<?php
/**
 * FooterRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-11 18:16:06
 * @modified   2022-08-11 18:16:06
 */

namespace Beike\Repositories;

use Beike\Models\Page;

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

        $content = $footerSetting['content'];
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
