<?php
/**
 * TranslationService.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-09-04 15:29:19
 * @modified   2023-09-04 15:29:19
 */

namespace Beike\Admin\Services;

use Beike\Services\TranslatorService;

class TranslationService
{
    /**
     * @param string       $source
     * @param array|string $targets
     * @param array|string $text
     * @return array
     * @throws \Exception
     */
    public static function translate(string $source, array|string $targets, array|string $text): array
    {
        if (empty($source) || empty($targets) || empty($text)) {
            return [];
        }

        $translator = self::getTranslator();
        $targets    = self::handleTargets($targets, $source);

        $items = [];
        foreach ($targets as $target) {
            try {
                $result = $error = '';
                if (is_array($text)) {
                    $result = $translator->batchTranslate($source, $target, $text);
                } elseif (is_string($text)) {
                    $result = $translator->translate($source, $target, $text);
                }
                $result = addslashes(str_replace('’', '\'', $result));
            } catch (\Exception $e) {
                $error  = $e->getMessage();
            }
            $item = [
                'locale' => $target,
                'result' => $result,
                'error'  => $error,
            ];
            $items[] = $item;
        }

        return $items;
    }

    /**
     * 获取翻译工具对象
     *
     * @return TranslatorService|null
     * @throws \Exception
     */
    private static function getTranslator(): ?TranslatorService
    {
        $translatorName = hook_filter('admin.service.translator', '');
        if (empty($translatorName)) {
            throw new \Exception(trans('admin/translation.empty_translator'));
        } elseif (! class_exists($translatorName)) {
            throw new \Exception(trans('admin/translation.class_not_found', ['translator_name' => $translatorName]));
        }

        $translator = new $translatorName;
        if (! $translator instanceof TranslatorService) {
            throw new \Exception("{$translatorName} should implement " . TranslatorService::class);
        }

        return $translator;
    }

    /**
     * @param $targets
     * @param $source
     * @return array
     */
    private static function handleTargets($targets, $source): array
    {
        if ($targets == 'all') {
            $targets = collect(locales())->where('code', '<>', $source)->pluck('code')->toArray();
        } elseif (is_string($targets)) {
            $targets = [$targets];
        }

        return $targets;
    }
}
