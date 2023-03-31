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

        return view('page_categories/home', $data);
    }

    public function show(PageCategory $pageCategory)
    {
        $breadCrumb = Breadcrumb::getInstance()
            ->addLink(shop_route('page_categories.home'), trans('page_category.index'));

        $data = [
            'category'               => new PageCategoryDetail($pageCategory),
            'active_page_categories' => PageCategoryRepo::getActiveList(),
            'breadcrumb'             => $breadCrumb,
            'category_pages'         => $pageCategory->pages()->paginate(12),
        ];

        return view('page_categories/show', $data);
    }
}
