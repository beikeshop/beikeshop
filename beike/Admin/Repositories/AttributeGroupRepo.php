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
                'name'   => $name,
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
                'name'   => $name,
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
        if ($id == 1) {
            throw new \Exception(trans('admin/attribute_group.error_cannot_delete_default_group'));
        }
        $group = AttributeGroup::query()->findOrFail($id);
        if ($group->attributes->count()) {
            throw new \Exception(trans('admin/attribute_group.error_cannot_delete_attribute_used', ['attributes' => implode(', ', $group->attributes->pluck('id')->toArray())]));
        }
        $group->descriptions()->delete();
        $group->delete();
    }
}
