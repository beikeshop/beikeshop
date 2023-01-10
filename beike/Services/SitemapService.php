<?php
/**
 * SitemapService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-10-09 17:34:34
 * @modified   2022-10-09 17:34:34
 */

namespace Beike\Services;

use Beike\Models\Brand;
use Beike\Models\Category;
use Beike\Models\Page;
use Beike\Models\Product;

class SitemapService
{
    public static function gen(): void
    {
        $sitemap = resolve('sitemap');

        $sitemap->add(shop_route('brands.index'), date('c'));
        $sitemap->add(shop_route('categories.index'), date('c'));
        $sitemap->add(shop_route('forgotten.index'), date('c'));
        $sitemap->add(shop_route('login.index'), date('c'));
        $sitemap->add(shop_route('products.search'), date('c'));
        $sitemap->add(shop_route('register.index'), date('c'));

        $products = Product::query()->where('active', 1)->orderBy('updated_at', 'desc')->get();
        foreach ($products as $item) {
            $sitemap->add(shop_route('products.show', ['product' => $item->id]), $item->updated_at->format('c'));
        }

        $brands = Brand::query()->where('status', 1)->orderBy('updated_at', 'desc')->get();
        foreach ($brands as $item) {
            $sitemap->add(shop_route('brands.show', ['id' => $item->id]), $item->updated_at->format('c'));
        }

        $categories = Category::query()->where('active', 1)->orderBy('updated_at', 'desc')->get();
        foreach ($categories as $item) {
            $sitemap->add(shop_route('categories.show', ['category' => $item->id]), $item->updated_at->format('c'));
        }

        $pages = Page::query()->where('active', 1)->orderBy('updated_at', 'desc')->get();
        foreach ($pages as $item) {
            $sitemap->add(shop_route('pages.show', ['page' => $item->id]), $item->updated_at->format('c'));
        }

        $sitemap->store('xml', 'sitemap');
    }
}
