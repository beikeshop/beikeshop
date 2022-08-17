<?php
/**
 * BreadCrumb.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-17 22:45:42
 * @modified   2022-08-17 22:45:42
 */

namespace Beike\Shop\View\Components;

use Beike\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public Collection $breadcrumbs;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type, $value, array $text = [])
    {
        $breadcrumbs[] = [
            'title' => trans('shop/common.home'),
            'url' => shop_route('home.index')
        ];

        if ($type == 'category') {
            $breadcrumbs = array_merge($breadcrumbs, $this->handleCategoryLinks($value));
        } elseif ($type == 'product') {
            $breadcrumbs = array_merge($breadcrumbs, $this->handleProductLinks($value));
        } elseif (Str::startsWith($value, 'account')) {
            $breadcrumbs = array_merge($breadcrumbs, $this->handleAccountLinks($value));
        } else {
            $breadcrumbs = array_merge($breadcrumbs, $this->handleLinks($type, $value, $text));
        }

        $this->breadcrumbs = collect($breadcrumbs);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|
     */
    public function render(): View
    {
        return view('components.breadcrumbs');
    }


    /**
     * 获取分类以及路径
     *
     * @param $value
     * @return array
     */
    private function handleCategoryLinks($value): array
    {
        $link = handle_link(['type' => 'category', 'value' => $value]);
        return [
            [
                'title' => $link['text'],
                'url' => $link['link'],
            ]
        ];
    }

    /**
     * 获取产品以及分类路径
     *
     * @param $value
     * @return array
     */
    private function handleProductLinks($value): array
    {
        $links = [];
        $productId = 0;
        if (is_array($value)) {
            $productId = $value['id'] ?? 0;
        } elseif (is_int($value)) {
            $productId = $value;
        }
        $product = Product::query()->find($productId);
        $category = $product->categories()->first();
        if ($category) {
            $categoryLink = handle_link(['type' => 'category', 'value' => $category]);
            $links[] = ['title' => $categoryLink['text'], 'url' => $categoryLink['link']];
        }

        $productLink = handle_link(['type' => 'product', 'value' => $value]);
        $links[] = ['title' => $productLink['text'], 'url' => $productLink['link']];

        return $links;
    }


    /**
     * 处理个人中心面包屑
     *
     * @param $value
     * @return array[]
     */
    private function handleAccountLinks($value): array
    {
        $links = [];
        $values = explode('.', $value);

        if (count($values) == 3) {
            $link = handle_link(['type' => 'static', 'value' => 'account.index']);
            $links[] = [
                'title' => $link['text'],
                'url' => $link['link'],
            ];
        }

        $link = handle_link(['type' => 'static', 'value' => $value]);
        $links[] = [
            'title' => $link['text'],
            'url' => $link['link'],
        ];

        return $links;
    }

    /**
     * 获取普通链接
     *
     * @param $type
     * @param $value
     * @param array $text
     * @return array
     */
    private function handleLinks($type, $value, array $text = []): array
    {
        $data = [
            'type' => $type,
            'value' => $value,
            'text' => $text,
        ];
        $link = handle_link($data);
        return [
            [
                'title' => $link['text'],
                'url' => $link['link'],
            ]
        ];
    }
}
