<?php

namespace Beike\Admin\Services;

use Beike\Repositories\LanguageRepo;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class LanguageService
{
    private static $models = [
        'AttributeDescription', 'AttributeGroupDescription', 'AttributeValueDescription', 'CategoryDescription',
        'CustomerGroupDescription', 'PageCategoryDescription', 'PageDescription', 'ProductDescription',
    ];

    public static function all(): array
    {
        $languages = LanguageRepo::all()->toArray();
        $languages = array_column($languages, null, 'code');

        $result = [];
        foreach (admin_languages() as $languageCode) {
            $langFile = resource_path("lang/$languageCode/admin/base.php");
            if (! is_file($langFile)) {
                throw new \Exception("File ($langFile) not exist!");
            }
            $baseData = require $langFile;
            $name     = $baseData['name'] ?? $languageCode;
            $result[] = [
                'code'       => $languageCode,
                'name'       => $name,
                'id'         => $languages[$languageCode]['id']         ?? 0,
                'image'      => $languages[$languageCode]['image']      ?? '',
                'sort_order' => $languages[$languageCode]['sort_order'] ?? '',
                'status'     => $languages[$languageCode]['status']     ?? '',
            ];
        }

        return $result;
    }

    public static function create($data)
    {
        $language = LanguageRepo::create($data);

        if ($language->code == system_setting('base.locale')) {
            return $language;
        }

        $models = self::$models;
        foreach ($models as $className) {
            $className = "\\Beike\\Models\\$className";
            $items     = $className::query()->where('locale', system_setting('base.locale', 'en'))->get()->toArray();
            foreach ($items as &$item) {
                if (isset($item['created_at'])) {
                    $item['created_at'] = now();
                }
                if (isset($item['updated_at'])) {
                    $item['updated_at'] = now();
                }
                unset($item['id']);
                $item['locale'] = $language->code;
            }
            $className::query()->insert($items);
        }

        return $language;
    }

    public static function delete($id)
    {
        $language = LanguageRepo::find($id);
        if (! $language) {
            return;
        }
        if ($language->code == system_setting('base.locale')) {
            throw new NotAcceptableHttpException(trans('admin/language.error_default_language_cannot_delete'));
        }
        LanguageRepo::delete($id);

        $models = self::$models;
        foreach ($models as $className) {
            $className = "\\Beike\\Models\\$className";
            $className::query()->where('locale', $language->code)->delete();
        }
    }
}
