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

namespace Beike\Repositories;

use Beike\Models\Category;
use Beike\Shop\Http\Resources\CategoryList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepo
{
    /**
     * 获取顶级及其子分类
     */
    public static function getTwoLevelCategories()
    {
        $topCategories = Category::query()
            ->from('categories as c')
            ->with(['description', 'children.description'])
            ->where('parent_id', 0)
            ->orderBy('position')
            ->get();

        $categoryList = CategoryList::collection($topCategories);
        return json_decode($categoryList->toJson(), true);
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

    public static function updateViewNumber()
    {

    }

    public static function multipleUpdate()
    {

    }


    public static function autocomplete($name)
    {
        $categories = Category::query()->with('paths.pathCategory.description')
            ->whereHas('paths', function ($query) use ($name) {
                $query->whereHas('pathCategory', function ($query) use ($name) {
                    $query->whereHas('description', function ($query) use ($name) {
                        $query->where('name', 'like', "{$name}%");
                    });
                });
            })
            ->limit(10)->get();
        $results = [];
        foreach ($categories as $category) {
            $pathName = '';
            foreach ($category->paths->sortBy('level') as $path) {
                if ($pathName) {
                    $pathName .= ' > ';
                }
                $pathName .= $path->pathCategory->description->name;
            }
            $results[] = [
                'id' => $category->id,
                'status' => $category->active,
                'name' => $pathName,
            ];
        }
        return $results;
    }

}

