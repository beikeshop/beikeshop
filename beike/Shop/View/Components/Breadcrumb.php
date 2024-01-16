<?php
/**
 * BreadCrumb.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-17 22:45:42
 * @modified   2022-08-17 22:45:42
 */

namespace Beike\Shop\View\Components;

use Beike\Models\Page;
use Beike\Models\PageCategory;
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
     * @throws \Exception
     */
    public function __construct($type, $value, array $text = [])
    {
        $breadcrumbs[] = [
            'title' => trans('shop/common.home'),
            'url'   => shop_route('home.index'),
        ];

        if ($type == 'category') {
            $breadcrumbs = array_merge($breadcrumbs, $this->handleCategoryLinks($value));
        } elseif ($type == 'product') {
            $breadcrumbs = array_merge($breadcrumbs, $this->handleProductLinks($value));
        } elseif ($type == 'order') {
            $breadcrumbs = array_merge($breadcrumbs, $this->handleOrderLinks($value));
        } elseif ($type == 'rma') {
            $breadcrumbs = array_merge($breadcrumbs, $this->handleRmaLinks($value));
        } elseif ($type == 'page') {
            $breadcrumbs = array_merge($breadcrumbs, $this->handlePageLinks($value));
        } elseif ($type == 'page_category') {
            $breadcrumbs = array_merge($breadcrumbs, $this->handlePageCategoryLinks($value));
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
     * @throws \Exception
     */
    private function handleCategoryLinks($value): array
    {
        $link = handle_link(['type' => 'category', 'value' => $value]);

        return [
            [
                'title' => $link['text'],
                'url'   => $link['link'],
            ],
        ];
    }

    /**
     * 获取商品以及分类路径
     *
     * @param $value
     * @return array
     * @throws \Exception
     */
    private function handleProductLinks($value): array
    {
        $links     = [];
        $productId = 0;
        if (is_array($value)) {
            $productId = $value['id'] ?? 0;
        } elseif (is_int($value)) {
            $productId = $value;
        }
        $product  = Product::query()->find($productId);
        $category = $product->categories()->first();
        if ($category) {
            $categoryLink = handle_link(['type' => 'category', 'value' => $category]);
            $links[]      = ['title' => $categoryLink['text'], 'url' => $categoryLink['link']];
        }

        $productLink = handle_link(['type' => 'product', 'value' => $value]);
        $links[]     = ['title' => $productLink['text'], 'url' => $productLink['link']];

        return $links;
    }

    /**
     * 获取订单详情页面包屑
     *
     * @param $value
     * @return array
     * @throws \Exception
     */
    private function handleOrderLinks($value): array
    {
        $links = [];

        $link    = handle_link(['type' => 'static', 'value' => 'account.index']);
        $links[] = [
            'title' => $link['text'],
            'url'   => $link['link'],
        ];

        $link    = handle_link(['type' => 'static', 'value' => 'account.order.index']);
        $links[] = [
            'title' => $link['text'],
            'url'   => $link['link'],
        ];

        $link    = handle_link(['type' => 'order', 'value' => $value]);
        $links[] = [
            'title' => $value,
            'url'   => $link['link'],
        ];

        return $links;
    }

    /**
     * 获取订单详情页面包屑
     *
     * @param $value
     * @return array
     * @throws \Exception
     */
    private function handleRmaLinks($value): array
    {
        $links = [];

        $link    = handle_link(['type' => 'static', 'value' => 'account.index']);
        $links[] = [
            'title' => $link['text'],
            'url'   => $link['link'],
        ];

        $link    = handle_link(['type' => 'static', 'value' => 'account.rma.index']);
        $links[] = [
            'title' => $link['text'],
            'url'   => $link['link'],
        ];

        $link    = handle_link(['type' => 'rma', 'value' => $value]);
        $links[] = [
            'title' => $value,
            'url'   => $link['link'],
        ];

        return $links;
    }

    /**
     * 获取文章页面包屑
     *
     * @param $value
     * @return array
     * @throws \Exception
     */
    private function handlePageLinks($value): array
    {
        $pageId = 0;
        if (is_array($value)) {
            $pageId = $value['id'] ?? 0;
        } elseif (is_int($value)) {
            $pageId = $value;
        }

        if (empty($pageId)) {
            return [];
        }

        $links    = [];
        $page     = Page::query()->find($pageId);
        $category = $page->category;
        if ($category) {
            $categoryLink = handle_link(['type' => 'page_category', 'value' => $category]);
            $links[]      = ['title' => $categoryLink['text'], 'url' => $categoryLink['link']];
        }

        $productLink = handle_link(['type' => 'page', 'value' => $value]);
        $links[]     = ['title' => $productLink['text'], 'url' => $productLink['link']];

        return $links;
    }

    /**
     * 获取文章页面包屑
     *
     * @param $value
     * @return array
     * @throws \Exception
     */
    private function handlePageCategoryLinks($value): array
    {
        $id = 0;
        if (is_array($value)) {
            $id = $value['id'] ?? 0;
        } elseif (is_int($value)) {
            $id = $value;
        }

        if (empty($id)) {
            return [];
        }

        $links    = [];
        $category = PageCategory::query()->find($id);
        if ($category) {
            $categoryLink = handle_link(['type' => 'page_category', 'value' => $category]);
            $links[]      = ['title' => $categoryLink['text'], 'url' => $categoryLink['link']];
        }

        return $links;
    }

    /**
     * 处理个人中心面包屑
     *
     * @param $value
     * @return array[]
     * @throws \Exception
     */
    private function handleAccountLinks($value): array
    {
        $links  = [];
        $values = explode('.', $value);

        if (count($values) == 3) {
            $link    = handle_link(['type' => 'static', 'value' => 'account.index']);
            $links[] = [
                'title' => $link['text'],
                'url'   => $link['link'],
            ];
        }

        $link    = handle_link(['type' => 'static', 'value' => $value]);
        $links[] = [
            'title' => $link['text'],
            'url'   => $link['link'],
        ];

        return $links;
    }

    /**
     * 获取普通链接
     *
     * @param       $type
     * @param       $value
     * @param array $text
     * @return array
     * @throws \Exception
     */
    private function handleLinks($type, $value, array $text = []): array
    {
        $data = [
            'type'  => $type,
            'value' => $value,
            'text'  => $text,
        ];
        $link = handle_link($data);

        return [
            [
                'title' => $link['text'],
                'url'   => $link['link'],
            ],
        ];
    }
}
