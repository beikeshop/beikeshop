<?php
/**
 * CategoryRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-16 17:45:41
 * @modified   2022-06-16 17:45:41
 */

namespace Beike\Repositories;

use Beike\Models\Category;
use Beike\Shop\Http\Resources\CategoryDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CategoryRepo
{
    private static $allCategoryWithName = null;

    /**
     * 后台获取分类列表
     * @return Builder[]|Collection
     */
    public static function getAdminList()
    {
        self::cleanCategories();

        return Category::with(['description', 'children.description', 'children.children.description'])
            ->where('parent_id', 0)
            ->get();
    }

    /**
     * 清理分类数据
     */
    public static function cleanCategories()
    {
        $categories = Category::with([
            'parent',
            'description',
        ])->get();

        foreach ($categories as $category) {
            if ($category->parent_id && empty($category->parent)) {
                $category->parent_id = 0;
                $category->save();
            }
            if (empty($category->description)) {
                $category->delete();
            }
        }
    }

    public static function flatten(string $locale, $includeInactive = true, $separator = ' > '): array
    {
        $sql = "SELECT cp.category_id AS id, TRIM(LOWER(GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '{$separator}'))) AS name, c1.parent_id, c1.position";
        $sql .= ' FROM category_paths cp';
        $sql .= ' LEFT JOIN categories c1 ON (cp.category_id = c1.id)';
        $sql .= ' LEFT JOIN categories c2 ON (cp.path_id = c2.id)';
        $sql .= ' LEFT JOIN category_descriptions cd1 ON (cp.path_id = cd1.category_id)';
        $sql .= " WHERE cd1.locale = '" . $locale . "' ";
        if (! $includeInactive) {
            $sql .= ' AND c1.active = 1 ';
        }
        $sql .= ' GROUP BY cp.category_id ORDER BY c1.position ASC, name ASC';

        return DB::select($sql);
    }

    /**
     * 生成每页产品数列表, 客户可以自己选择如何分页
     *
     * @return array
     */
    public static function getPerPages(): array
    {
        $perPages      = [];
        $configPerPage = system_setting('base.product_per_page', 20);
        for ($index = 1; $index <= 5; $index++) {
            $perPages[] = $configPerPage * $index;
        }

        return $perPages;
    }

    /**
     * 获取顶级及其子分类
     */
    public static function getTwoLevelCategories()
    {
        $topCategories = Category::query()
            ->from('categories as c')
            ->with(['description', 'activeChildren.description'])
            ->where('parent_id', 0)
            ->where('active', true)
            ->orderBy('position')
            ->get();

        $categoryList = CategoryDetail::collection($topCategories);

        return json_decode($categoryList->toJson(), true);
    }

    /**
     * 获取商品分类列表
     *
     * @param array $filters
     * @return Builder[]|Collection
     */
    public static function list(array $filters = [])
    {
        $builder = self::getBuilder($filters);

        return $builder->get();
    }

    /**
     * 获取筛选builder
     *
     * @param array $filters
     * @return Builder
     */
    public static function getBuilder(array $filters = []): Builder
    {
        $builder = Category::query()->with(['description']);
        $keyword = $filters['keyword'] ?? '';
        if ($keyword) {
            $builder->whereHas('description', function ($query) use ($keyword) {
                return $query->where('name', 'like', "%$keyword%");
            });
        }

        return $builder;
    }

    /**
     * 自动完成
     *
     * @param $name
     * @return array
     */
    public static function autocomplete($name): array
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
                if (empty($path->pathCategory)) {
                    $path->delete();

                    continue;
                }
                $pathName .= $path->pathCategory->description->name;
            }
            $results[] = [
                'id'     => $category->id,
                'status' => $category->active,
                'name'   => $pathName,
            ];
        }

        return $results;
    }

    /**
     * 删除商品分类
     * @throws \Exception
     */
    public static function delete($category)
    {
        if (is_int($category)) {
            $category = Category::query()->findOrFail($category);
        } elseif (! ($category instanceof Category)) {
            throw new \Exception('invalid category');
        }
        $category->descriptions()->delete();
        $category->paths()->delete();
        $category->productCategories()->delete();
        $category->delete();
    }

    /**
     * 通过分类ID获取商品名称
     * @param $category
     * @return mixed|string
     */
    public static function getName($category)
    {
        $id         = is_int($category) ? $category : $category->id;
        $categories = self::getAllCategoriesWithName();

        return $categories[$id]['name'] ?? '';
    }

    /**
     * 获取所有商品分类ID和名称列表
     * @return array|null
     */
    public static function getAllCategoriesWithName(): ?array
    {
        if (self::$allCategoryWithName !== null) {
            return self::$allCategoryWithName;
        }

        $items      = [];
        $categories = self::getBuilder()->select('id')->get();
        foreach ($categories as $category) {
            $items[$category->id] = [
                'id'   => $category->id,
                'name' => $category->description->name ?? '',
            ];
        }

        return self::$allCategoryWithName = $items;
    }
}
