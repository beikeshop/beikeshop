<?php
/**
 * TaxRateRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-27 11:21:14
 * @modified   2022-07-27 11:21:14
 */

namespace Beike\Admin\Repositories;

use Beike\Models\TaxRate;

class TaxRateRepo
{
    public static function getList()
    {
        return TaxRate::query()->with([
            'region'
        ])->get();
    }

    public static function createOrUpdate($data)
    {
        $id = $data['id'] ?? 0;
        if ($id) {
            $taxRate = TaxRate::query()->findOrFail($id);
        } else {
            $taxRate = new TaxRate();
        }
        $taxRate->fill([
            'region_id' => $data['region_id'],
            'name' => $data['name'],
            'rate' => $data['rate'],
            'type' => $data['type'],
        ]);
        $taxRate->saveOrFail();
        return $taxRate;
    }

    public static function deleteById($id)
    {
        $taxRate = TaxRate::query()->findOrFail($id);
        $taxRate->delete();
    }
}
