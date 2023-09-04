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
     * @throws \Exception
     */
    public static function translate($source, $target, $text): array
    {
        if (empty($source) || empty($target) || empty($text)) {
            return [];
        }

        $translatorName = hook_filter('admin.service.translator', '');
        if (empty($translatorName)) {
            throw new \Exception('Empty translator name');
        } elseif (! class_exists($translatorName)) {
            throw new \Exception("Cannot found the class {$translatorName}");
        }

        $translator = new $translatorName;
        if (! $translator instanceof TranslatorService) {
            throw new \Exception("{$translatorName} should implement " . TranslatorService::class);
        }

        if ($target == 'all') {
            $target = collect(locales())->where('code', '<>', $source)->pluck('code')->toArray();
        } elseif (is_string($target)) {
            $target = [$target];
        }

        $items      = [];
        foreach ($target as $toLocale) {
            try {
                $error  = '';
                $result = $translator->translate($source, $toLocale, $text);
            } catch (\Exception $e) {
                $result = $text;
                $error  = $e->getMessage();
            }
            $item = [
                'locale' => $toLocale,
                'result' => $result,
                'error'  => $error,
            ];
            $items[] = $item;
        }

        return $items;
    }
}
