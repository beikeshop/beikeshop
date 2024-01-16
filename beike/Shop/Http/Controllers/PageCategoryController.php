<?php

/**
 * PageCategoryController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-02-10 11:39:46
 * @modified   2023-02-10 11:39:46
 */

namespace Beike\Shop\Http\Controllers;

use Beike\Libraries\Breadcrumb;
use Beike\Models\PageCategory;
use Beike\Repositories\PageCategoryRepo;
use Beike\Repositories\PageRepo;
use Beike\Shop\Http\Resources\PageCategoryDetail;

class PageCategoryController extends Controller
{
    public function home()
    {
        $breadCrumb = Breadcrumb::getInstance()
            ->addLink(shop_route('page_categories.home'), trans('page_category.index'));

        $data = [
            'breadcrumb'             => $breadCrumb,
            'active_page_categories' => PageCategoryRepo::getActiveList(),
            'active_pages'           => PageRepo::getCategoryPages(),
        ];
        $data = hook_filter('page_categories.home.data', $data);

        return view('page_categories/home', $data);
    }

    public function show(PageCategory $pageCategory)
    {
        $breadCrumb = Breadcrumb::getInstance()
            ->addLink(shop_route('page_categories.home'), trans('page_category.index'));

        $categoryPages = PageRepo::getBuilder(['page_category_id' => $pageCategory->id])->paginate(12);

        $data = [
            'category'               => new PageCategoryDetail($pageCategory),
            'active_page_categories' => PageCategoryRepo::getActiveList(['limit' => 5]),
            'breadcrumb'             => $breadCrumb,
            'category_pages'         => $categoryPages,
        ];
        $data = hook_filter('page_categories.show.data', $data);

        return view('page_categories/show', $data);
    }
}
