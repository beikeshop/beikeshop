<?php
/**
 * PageRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-26 21:08:07
 * @modified   2022-07-26 21:08:07
 */

namespace Beike\Admin\Repositories;

use Beike\Models\Page;

class PageRepo
{
    public static function getList()
    {
        return Page::query()->with([
            'descriptions'
        ])->get();
    }

    public static function createOrUpdate($data)
    {
        $id = $data['id'] ?? 0;
        if ($id) {
            $page = Page::query()->findOrFail($id);
        } else {
            $page = new Page();
        }
        $page->fill([
            'position' => $data['position'],
            'active' => $data['active'],
        ]);
        $page->saveOrFail();

        $page->descriptions()->delete();
        $page->descriptions()->createMany($data['descriptions']);
        $page->load(['descriptions']);
        return $page;
    }

    public static function deleteById($id)
    {
        $page = Page::query()->findOrFail($id);
        $page->descriptions()->delete();
        $page->delete();
    }
}
