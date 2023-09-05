<?php
/**
 * Youdao.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-09-04 15:31:23
 * @modified   2023-09-04 15:31:23
 */

namespace Plugin\Youdao\Services;

use Beike\Services\TranslatorService;
use Plugin\Youdao\Libraries\Youdao;

class YoudaoService implements TranslatorService
{
    private Youdao $translator;

    public function __construct()
    {
        $appKey           = plugin_setting('youdao.app_key');
        $appSecret        = plugin_setting('youdao.app_secret');
        $this->translator = new Youdao($appKey, $appSecret);
    }

    /**
     * @throws \Exception
     */
    public function translate($from, $to, $text): string
    {
        $from = $this->mapCode($from);
        $to   = $this->mapCode($to);

        return $this->translator->translate($text, $from, $to);
    }

    /**
     * @param $code
     * @return string
     */
    public function mapCode($code): string
    {
        $map = [
            'de'    => 'de',
            'en'    => 'en',
            'es'    => 'es',
            'fr'    => 'fr',
            'id'    => 'id',
            'it'    => 'it',
            'ja'    => 'ja',
            'ko'    => 'ko',
            'ru'    => 'ru',
            'zh_cn' => 'zh-CHS',
            'zh_hk' => 'zh-CHT',
        ];

        return $map[$code] ?? 'en';
    }
}
