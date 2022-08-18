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
    public static function getList()
    {
        return Region::query()->with('regionZones.zone')->get();
    }

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

        $newRegionZones = [];
        foreach ($data['region_zones'] as $regionZone) {
            if ($regionZone['country_id'] && $regionZone['zone_id']) {
                $newRegionZones[] = [
                    'country_id' => $regionZone['country_id'],
                    'zone_id' => $regionZone['zone_id'],
                ];
            }
        }
        if ($newRegionZones) {
            $region->regionZones()->delete();
            $region->regionZones()->createMany($newRegionZones);
        }
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
