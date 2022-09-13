<?php
/**
 * PageRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-15 12:37:25
 * @modified   2022-08-15 12:37:25
 */

namespace Beike\Repositories;

use Beike\Models\Page;
use Illuminate\Database\Eloquent\Builder;

class PageRepo
{
    private static $allPagesWithName;

    public static function getBuilder(): Builder
    {
        return Page::query()->with('description');
    }

    /**
     * 通过品牌ID获取单页名称
     * @param $id
     * @return mixed|string
     */
    public static function getName($id)
    {
        $categories = self::getAllPagesWithName();
        return $categories[$id]['name'] ?? '';
    }


    /**
     * 获取所有单页ID和名称列表
     * @return array|null
     */
    public static function getAllPagesWithName(): ?array
    {
        if (self::$allPagesWithName !== null) {
            return self::$allPagesWithName;
        }

        $items = [];
        $pages = self::getBuilder()->select('id')->get();
        foreach ($pages as $brand) {
            $items[$brand->id] = [
                'id' => $brand->id,
                'name' => $brand->description->title ?? '',
            ];
        }
        return self::$allPagesWithName = $items;
    }
}
