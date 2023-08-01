<?php
/**
 * AttributeRepo.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2023-01-04 19:45:41
 * @modified   2023-01-04 19:45:41
 */

namespace Beike\Admin\Repositories;

use Beike\Models\Attribute;
use Beike\Models\AttributeDescription;
use Beike\Models\AttributeValue;
use Beike\Models\ProductAttribute;

class AttributeRepo
{
    public static function getList()
    {
        return Attribute::query()->orderByDesc('id')->paginate()->withQueryString();
    }

    public static function create($data)
    {
        $attribute = Attribute::query()->create([
            'attribute_group_id' => $data['attribute_group_id'],
            'sort_order'         => $data['sort_order'],
        ]);

        $descriptions = [];
        foreach ($data['name'] as $locale => $name) {
            $descriptions[] = [
                'locale' => $locale,
                'name'   => $name,
            ];
        }
        $attribute->descriptions()->createMany($descriptions);

        return $attribute;
    }

    public static function update($id, $data)
    {
        $attribute = Attribute::query()->updateOrCreate(['id' => $id], [
            'attribute_group_id' => $data['attribute_group_id'],
            'sort_order'         => $data['sort_order'],
        ]);

        $descriptions = [];
        foreach ($data['name'] as $locale => $name) {
            $descriptions[] = [
                'locale' => $locale,
                'name'   => $name,
            ];
        }
        $attribute->descriptions()->delete();
        $attribute->descriptions()->createMany($descriptions);

        return $attribute;
    }

    public static function createValue($data)
    {
        $attributeValue = AttributeValue::query()->create([
            'attribute_id' => $data['attribute_id'],
        ]);

        $descriptions = [];
        foreach ($data['name'] as $locale => $name) {
            $descriptions[] = [
                'locale' => $locale,
                'name'   => $name,
            ];
        }
        $attributeValue->descriptions()->createMany($descriptions);

        return $attributeValue;
    }

    public static function updateValue($id, $data)
    {
        $attributeValue = AttributeValue::query()->findOrFail($id);

        $descriptions = [];
        foreach ($data['name'] as $locale => $name) {
            $descriptions[] = [
                'locale' => $locale,
                'name'   => $name,
            ];
        }
        $attributeValue->descriptions()->delete();
        $attributeValue->descriptions()->createMany($descriptions);

        return $attributeValue;
    }

    public static function deleteValue($id)
    {
        $productIds = ProductAttribute::query()->where('attribute_value_id', $id)->pluck('product_id')->toArray();
        if ($productIds) {
            throw new \Exception(trans('admin/attribute.error_cannot_delete_product_used', ['product_ids' => implode(', ', $productIds)]));
        }
        AttributeValue::query()->findOrFail($id)->delete();
    }

    public static function find($id)
    {
        return Attribute::query()->with('values.descriptions')->find($id);
    }

    public static function delete($id)
    {
        $productIds = ProductAttribute::query()->where('attribute_id', $id)->pluck('product_id')->toArray();
        if ($productIds) {
            throw new \Exception(trans('admin/attribute.error_cannot_delete_product_used', ['product_ids' => implode(', ', $productIds)]));
        }
        $attribute = Attribute::query()->findOrFail($id);
        $attribute->descriptions()->delete();
        $attribute->values()->delete();
        $attribute->delete();
    }

    public static function autocomplete($name)
    {
        $builder = Attribute::query()->with('description')
            ->whereHas('description', function ($query) use ($name) {
                $query->where('name', 'like', "{$name}%");
            });

        return $builder->limit(10)->get();
    }

    public static function autocompleteValue($attributeId, $name)
    {
        $builder = AttributeValue::query()->with('description')
            ->where('attribute_id', $attributeId)
            ->whereHas('description', function ($query) use ($name) {
                $query->where('name', 'like', "{$name}%");
            });

        return $builder->limit(10)->get();
    }

    public static function getByIds($ids)
    {
        return AttributeDescription::query()
            ->where('locale', locale())
            ->whereIn('attribute_id', $ids)
            ->select(['attribute_id as id', 'name'])
            ->get()
            ->toArray();
    }
}
