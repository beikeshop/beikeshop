<?php
/**
 * AttributeGroupRepo.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2023-01-04 19:45:41
 * @modified   2023-01-04 19:45:41
 */

namespace Beike\Admin\Repositories;

use Beike\Models\AttributeGroup;

class AttributeGroupRepo
{
    public static function getList()
    {
        return AttributeGroup::query()->orderByDesc('id')->with('description', 'descriptions')->get();
    }

    public static function create($data)
    {
        $attributeGroup = AttributeGroup::query()->create([
            'sort_order' => $data['sort_order'],
        ]);

        $descriptions = [];
        foreach ($data['name'] as $locale => $name) {
            $descriptions[] = [
                'locale' => $locale,
                'name' => $name,
            ];
        }
        $attributeGroup->descriptions()->createMany($descriptions);

        $attributeGroup->load('description', 'descriptions');

        return $attributeGroup;
    }

    public static function update($id, $data)
    {
        $attributeGroup = AttributeGroup::query()->updateOrCreate(['id' => $id], [
            'sort_order' => $data['sort_order'],
        ]);

        $descriptions = [];
        foreach ($data['name'] as $locale => $name) {
            $descriptions[] = [
                'locale' => $locale,
                'name' => $name,
            ];
        }
        $attributeGroup->descriptions()->delete();
        $attributeGroup->descriptions()->createMany($descriptions);

        $attributeGroup->load('description', 'descriptions');

        return $attributeGroup;
    }

    public static function find($id)
    {
        return AttributeGroup::query()->find($id);
    }

    public static function delete($id)
    {
        $group = AttributeGroup::query()->findOrFail($id);
        $group->delete();
    }
}
