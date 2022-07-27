<?php
/**
 * RegionRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-27 10:48:25
 * @modified   2022-07-27 10:48:25
 */

namespace Beike\Admin\Repositories;

use Beike\Models\Region;

class RegionRepo
{
    public static function createOrUpdate($data)
    {
        $id = $data['id'] ?? 0;
        if ($id) {
            $region = Region::query()->findOrFail($id);
        } else {
            $region = new Region();
        }
        $region->fill([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
        $region->saveOrFail();

        $region->regionZones()->delete();
        $region->regionZones()->createMany($data['region_zones']);
        $region->load(['regionZones']);
        return $region;
    }

    public static function deleteById($id)
    {
        $region = Region::query()->findOrFail($id);
        $region->regionZones()->delete();
        $region->delete();
    }
}
