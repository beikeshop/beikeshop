<?php
/**
 * CategoryRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-16 17:45:41
 * @modified   2022-06-16 17:45:41
 */

namespace Beike\Shop\Repositories;

use Beike\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Beike\Shop\Http\Resources\CategoryItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryRepo
{
    /**
     * 获取顶级及其子分类
     */
    public static function getTwoLevelCategories(): AnonymousResourceCollection
    {
        $topCategories = Category::query()
            ->from('categories as c')
            ->with(['description', 'children.description'])
            ->where('parent_id', 0)
            ->get();

        return CategoryItem::collection($topCategories);
    }


    /**
     * 获取产品分类列表
     *
     * @param array $filter , keyword, a_name, b_name, category_page, per_page
     * @return Builder[]|Collection
     */
    public static function list(array $filter = [])
    {
        $keyword = $filter['keyword'] ?? '';
        $builder = Category::query()->with(['description']);
        if ($keyword) {
            // $builder->whereExists('name')
        }
        return $builder->get();
    }

    /**
     * @param $categoryData
     */
    public static function create($categoryData)
    {
        // Category::query()->create($categoryData);
        $category = new Category();
        $category->parent_id = 12;
    }

    public static function update(Category $category)
    {
        $category->update([

        ]);
    }

    public static function updateViewNumber()
    {

    }

    public static function multipleUpdate()
    {

    }
}

