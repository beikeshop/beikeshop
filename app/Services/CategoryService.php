<?php
/**
 * CategoryService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Sam Chen <sam.chen@opencart.cn>
 * @created    2022-05-07 15:15:25
 * @modified   2022-05-07 15:15:25
 */

namespace App\Services;

use App\Models\Category;
use App\Models\CategoryPath;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public function create(array $data)
    {
        try {
            DB::beginTransaction();

            $category = new \App\Models\Category();
            $category->fill($data);
            $category->saveOrFail();

            $descriptions = [];
            foreach ($data['descriptions'] as $description) {
                $descriptions[] = [
                    'locale' => $description['locale'],
                    'name' => $description['name'],
                    'content' => $description['content'] ?? '',
                    'meta_title' => $description['meta_title'] ?? '',
                    'meta_description' => $description['meta_description'] ?? '',
                    'meta_keyword' => $description['meta_keyword'] ?? '',
                ];
            }
            $category->descriptions()->createMany($descriptions);

            $this->createPath($category);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $category;
    }

    public function update(Category $category, array $data)
    {
        try {
            DB::beginTransaction();

            $category->updateOrFail($data);

            $descriptions = [];
            foreach ($data['descriptions'] as $description) {
                $descriptions[] = [
                    'locale' => $description['locale'],
                    'name' => $description['name'],
                    'content' => $description['content'] ?? '',
                    'meta_title' => $description['meta_title'] ?? '',
                    'meta_description' => $description['meta_description'] ?? '',
                    'meta_keyword' => $description['meta_keyword'] ?? '',
                ];
            }
            $category->descriptions()->delete();
            $category->descriptions()->createMany($descriptions);

            $this->updatePath($category);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $category;
    }

    public function createPath(Category $category)
    {
        // Paths
        $paths = [];
        // 复制上级分类的 paths
        $level = 0;
        $parentPaths = CategoryPath::query()->where('category_id', $category->parent_id)->orderBy('level')->get();
        foreach ($parentPaths as $path) {
            $paths[] = [
                'path_id' => $path->path_id,
                'level' => $level,
            ];
            $level++;
        }
        // 自身
        $paths[] = [
            'path_id' => $category->id,
            'level' => $level,
        ];
        $category->paths()->createMany($paths);
    }

    public function updatePath(Category $category)
    {
        $categoryPaths = CategoryPath::query()
            ->where('path_id', $category->id)
            ->orderBy('level')
            ->get();

        // Get the nodes new parents
        $newParentPathIds = CategoryPath::query()
            ->where('category_id', $category->parent_id)
            ->orderBy('level')
            ->pluck('path_id')
            ->toArray();

        $paths = [];
        if ($categoryPaths->count()) {
            foreach ($categoryPaths as $category_path) {
                $newPathIds = $newParentPathIds;

                $results = CategoryPath::query()
                    ->where('category_id', (int)$category_path->category_id)
                    ->where('level', '>=', $category_path->level)
                    ->orderBy('level')
                    ->get();

                foreach ($results as $result) {
                    $newPathIds[] = $result->path_id;
                }

                $level = 0;
                foreach ($newPathIds as $path_id) {
                    $paths[] = [
                        'category_id' => $category_path->category_id,
                        'path_id' => $path_id,
                        'level' => $level,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $level++;
                }
            }
        }

        CategoryPath::query()
            ->whereIn('category_id', $categoryPaths->pluck('category_id'))
            ->delete();
        CategoryPath::insert($paths);
    }

}
