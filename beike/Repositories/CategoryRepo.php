<?php
/**
 * CategoryRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-06-16 17:45:41
 * @modified   2022-06-16 17:45:41
 */

namespace Beike\Repositories;

use Beike\Models\Category;
use Beike\Models\CategoryPath;
use Beike\Shop\Http\Resources\CategoryDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CategoryRepo
{
    private static $allCategoryWithName = null;

    private const CACHE_TTL = 86400;

    private const CACHE_VERSION_KEY = 'category_cache_version';

    /**
     * 后台获取分类列表
     * @return Builder[]|Collection
     */
    public static function getAdminList()
    {
        self::cleanCategories();

        $cacheKey = self::cacheKey('admin_list', [locale()]);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return Category::with(['description', 'children.description', 'children.children.description'])
                ->where('parent_id', 0)
                ->get();
        });
    }

    /**
     * 清理分类数据
     */
    public static function cleanCategories()
    {
        $cacheKey = self::cacheKey('cleaned');
        if (Cache::has($cacheKey)) {
            return;
        }

        $changed = false;

        Category::query()
            ->select(['id', 'parent_id'])
            ->with(['parent:id'])
            ->withCount('descriptions')
            ->chunkById(500, function ($categories) use (&$changed) {
                foreach ($categories as $category) {
                    if ($category->parent_id && empty($category->parent)) {
                        $category->parent_id = 0;
                        $category->save();
                        $changed = true;
                    }

                    if ((int) $category->descriptions_count === 0) {
                        $category->delete();
                        $changed = true;
                    }
                }
            });

        if ($changed) {
            self::clearCache();
        } else {
            Cache::put($cacheKey, true, 3600);
        }
    }

    public static function flatten(string $locale, $includeInactive = true, $separator = ' > ')
    {
        $cacheKey = self::cacheKey('flatten', [$locale, $includeInactive, $separator]);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($locale, $includeInactive, $separator) {
            $aggregateFunc = getDBDriver() == 'mysql'
                ? "GROUP_CONCAT(cd.name ORDER BY cp.level SEPARATOR '{$separator}')"
                : "STRING_AGG(cd.name, '{$separator}' ORDER BY cp.level)";

            return CategoryPath::query()
                ->select([
                    'categories.id',
                    DB::raw("TRIM(LOWER({$aggregateFunc})) as name"),
                    'categories.parent_id',
                    'categories.position'
                ])
                ->from('category_paths as cp')
                ->join('categories', 'cp.category_id', '=', 'categories.id')
                ->join('category_descriptions as cd', function($join) use ($locale) {
                    $join->on('cp.path_id', '=', 'cd.category_id')
                        ->where('cd.locale', $locale);
                })
                ->when(!$includeInactive, function ($query) {
                    $query->where('categories.active', true);
                })
                ->groupBy('categories.id', 'categories.parent_id', 'categories.position')
                ->orderBy('categories.position')
                ->orderBy('name')
                ->get();
        });
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
        $cacheKey = self::cacheKey('two_level', [locale()]);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            $topCategories = Category::query()
                ->from('categories as c')
                ->with(['description', 'activeChildren.description'])
                ->where('parent_id', 0)
                ->where('active', true)
                ->orderBy('position')
                ->get();

            $categoryList = CategoryDetail::collection($topCategories);

            return json_decode($categoryList->toJson(), true);
        });
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
                        $query->where('name', 'like', "%{$name}%");
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
    public static function delete($category, bool $clearCache = true)
    {
        if (is_int($category)) {
            $category = Category::query()->findOrFail($category);
        } elseif (! ($category instanceof Category)) {
            throw new \Exception('invalid category');
        }

        foreach ($category->children as $child) {
            self::delete($child, false);
        }

        $category->descriptions()->delete();
        $category->paths()->delete();
        $category->productCategories()->delete();
        $category->delete();

        if ($clearCache) {
            self::clearCache();
        }
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

        $cacheKey = self::cacheKey('names', [locale()]);

        return self::$allCategoryWithName = Cache::remember($cacheKey, self::CACHE_TTL, function () {
            $items        = [];
            $categoryIds = self::getBuilder()->select('categories.id')->pluck('id');

            if ($categoryIds->isNotEmpty()) {
                $names = DB::table('category_descriptions')
                    ->whereIn('category_id', $categoryIds)
                    ->where('locale', locale())
                    ->select(['category_id', 'name'])
                    ->get();

                foreach ($names as $row) {
                    $items[$row->category_id] = [
                        'id'   => $row->category_id,
                        'name' => $row->name ?? '',
                    ];
                }
            }

            return $items;
        });
    }

    public static function clearCache(): void
    {
        self::$allCategoryWithName = null;

        Cache::forever(self::CACHE_VERSION_KEY, (string) microtime(true));
    }

    public static function cacheVersion(): string
    {
        return (string) Cache::get(self::CACHE_VERSION_KEY, '1');
    }

    private static function cacheKey(string $name, array $parts = []): string
    {
        $parts[] = self::cacheVersion();

        return 'category.' . $name . '.' . md5(json_encode($parts));
    }
}
