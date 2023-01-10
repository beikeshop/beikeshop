<?php
/**
 * TaxClassRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-26 21:08:07
 * @modified   2022-07-26 21:08:07
 */

namespace Beike\Admin\Repositories;

use Beike\Admin\Http\Resources\TaxClassDetail;
use Beike\Models\TaxClass;

class TaxClassRepo
{
    public const BASE_TYPES = ['store', 'payment', 'shipping'];

    public static function getList()
    {
        $taxClass = TaxClass::query()->with([
            'taxRates.region',
            'taxRules',
        ])->get();

        return TaxClassDetail::collection($taxClass)->jsonSerialize();
    }

    public static function createOrUpdate($data)
    {
        $id = $data['id'] ?? 0;
        if ($id) {
            $taxClass = TaxClass::query()->findOrFail($id);
        } else {
            $taxClass = new TaxClass();
        }
        $taxClass->fill([
            'title'       => $data['title'],
            'description' => $data['description'],
        ]);
        $taxClass->saveOrFail();

        $rules = [];
        foreach ($data['tax_rules'] as $rule) {
            $rules[] = [
                'tax_rate_id' => $rule['tax_rate_id'],
                'based'       => $rule['based'],
                'priority'    => (int) $rule['priority'],
            ];
        }
        $taxClass->taxRules()->delete();
        $taxClass->taxRules()->createMany($rules);
        $taxClass->load(['taxRules']);

        return $taxClass;
    }

    public static function deleteById($id)
    {
        $taxClass = TaxClass::query()->findOrFail($id);
        $taxClass->taxRules()->delete();
        $taxClass->delete();
    }
}
