<?php
/**
 * TaxClassRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-26 21:08:07
 * @modified   2022-07-26 21:08:07
 */

namespace Beike\Admin\Repositories;

use Beike\Models\TaxClass;

class TaxClassRepo
{
    const BASE_TYPES = ['store', 'payment', 'shipping'];

    public static function getList()
    {
        return TaxClass::query()->with([
            'taxRates.region',
            'taxRules'
        ])->get();
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
            'title' => $data['title'],
            'description' => $data['description'],
        ]);
        $taxClass->saveOrFail();

        $taxClass->taxRules()->delete();
        $taxClass->taxRules()->createMany($data['tax_rules']);
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
