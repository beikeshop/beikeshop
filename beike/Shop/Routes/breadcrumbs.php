<?php
/**
 * breadcrumbs.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-26 17:26:37
 * @modified   2022-07-26 17:26:37
 */

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('首页', shop_route('home.index'));
});

// Home > [Category]
Breadcrumbs::for('category', function (BreadcrumbTrail $trail, $category) {
    $trail->parent('home');
    $trail->push($category->description->name, shop_route('categories.show', $category));
});

// Home > Category > [Product]
Breadcrumbs::for('product', function (BreadcrumbTrail $trail, $product) {
    $productModel = \Beike\Models\Product::query()->find($product['id']);
    $category = $productModel->categories->first();
    if ($category) {
        $trail->parent('category', $category);
    } else {
        $trail->parent('home');
    }
    $trail->push($product['name'], shop_route('products.show', $productModel));
});

