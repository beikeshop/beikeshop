<?php
/**
 * Url.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-03-15 14:13:39
 * @modified   2023-03-15 14:13:39
 */

namespace Beike\Libraries;

use Beike\Repositories\BrandRepo;
use Beike\Repositories\CategoryRepo;
use Beike\Repositories\PageCategoryRepo;
use Beike\Repositories\PageRepo;
use Beike\Repositories\ProductRepo;
use Illuminate\Support\Str;

class Url
{
    public const TYPES = [
        'category', 'product', 'brand', 'page', 'page_category', 'order', 'rma', 'static', 'custom',
    ];

    public static function getInstance(): self
    {
        return new self();
    }

    /**
     * Handle link.
     *
     * @return mixed|string
     * @throws \Exception
     */
    public function link($type, $value)
    {
        if (empty($type) || empty($value) || ! in_array($type, self::TYPES)) {
            return '';
        }

        if (is_array($value)) {
            throw new \Exception('Value must be integer, string or object');
        }

        if ($type == 'category') {
            if (! $value instanceof \Beike\Models\Category) {
                $value = \Beike\Models\Category::query()->find($value);
            }

            return $value->url ?? '';
        } elseif ($type == 'product') {
            if (! $value instanceof \Beike\Models\Product) {
                $value = \Beike\Models\Product::query()->find($value);
            }

            return $value->url ?? '';
        } elseif ($type == 'brand') {
            if (! $value instanceof \Beike\Models\Brand) {
                $value = \Beike\Models\Brand::query()->find($value);
            }

            return $value->url ?? '';
        } elseif ($type == 'page') {
            if (! $value instanceof \Beike\Models\Page) {
                $value = \Beike\Models\Page::query()->where('active', 1)->find($value);
            }

            return $value->url ?? '';
        } elseif ($type == 'page_category') {
            if (! $value instanceof \Beike\Models\PageCategory) {
                $value = \Beike\Models\PageCategory::query()->find($value);
            }

            return $value->url ?? '';
        } elseif ($type == 'order') {
            return shop_route('account.order.show', ['number' => $value]);
        } elseif ($type == 'rma') {
            return shop_route('account.rma.show', ['id' => $value]);
        } elseif ($type == 'static') {
            return shop_route($value);
        } elseif ($type == 'custom') {
            if (Str::startsWith($value, ['http://', 'https://'])) {
                return $value;
            }

            return "//{$value}";
        }

        return '';
    }

    /**
     * Handle link label
     *
     * @param $type
     * @param $value
     * @param $texts
     * @return mixed
     */
    public function label($type, $value, $texts)
    {
        $types = ['category', 'product', 'brand', 'page', 'page_category', 'static', 'custom'];
        if (empty($type) || empty($value) || ! in_array($type, $types)) {
            return '';
        }

        $locale = locale();
        $text   = $texts[$locale] ?? '';
        if ($text) {
            return $text;
        }

        if ($type == 'category') {
            return CategoryRepo::getName($value);
        } elseif ($type == 'product') {
            return ProductRepo::getName($value);
        } elseif ($type == 'brand') {
            return BrandRepo::getName($value);
        } elseif ($type == 'page') {
            return PageRepo::getName($value);
        } elseif ($type == 'page_category') {
            return PageCategoryRepo::getName($value);
        } elseif ($type == 'static') {
            $value = $this->handleLocale($value);

            return trans('shop/' . $value);
        } elseif ($type == 'custom') {
            return $text;
        }

        return '';
    }

    /**
     * preg_replace('/\/([^\/]+)$/', '.$1', str_replace(".", "/", $value));
     *
     * @param $value
     * @return string
     */
    private function handleLocale($value): string
    {
        $parts = explode('.', $value);
        if (count($parts) < 2) {
            return $value;
        }

        $result = '';
        foreach ($parts as $index => $part) {
            if ($index < count($parts) - 2) {
                $result .= $part . '/';
            } elseif ($index < count($parts) - 1) {
                $result .= $part . '.';
            } else {
                $result .= $part;
            }
        }

        return $result;
    }
}
