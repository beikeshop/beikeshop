<?php
/**
 * RegionRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-27 10:48:25
 * @modified   2022-07-27 10:48:25
 */

namespace Beike\Admin\Repositories;

use Beike\Models\Region;
use Illuminate\Support\Facades\DB;

class RegionRepo
{
    public static function getList()
    {
        return Region::query()->with('regionZones.zone')->get();
    }

    public static function createOrUpdate($data)
    {
        try {
            DB::beginTransaction();
            $region = self::pushRegion($data);
            DB::commit();

            return $region;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public static function pushRegion($data)
    {
        $id = $data['id'] ?? 0;
        if ($id) {
            $region = Region::query()->findOrFail($id);
        } else {
            $region = new Region();
        }
        $region->fill([
            'name'        => $data['name'],
            'description' => $data['description'],
        ]);
        $region->saveOrFail();

        $newRegionZones = [];
        foreach ($data['region_zones'] as $regionZone) {
            if ($regionZone['country_id']) {
                $newRegionZones[] = [
                    'country_id' => (int) $regionZone['country_id'],
                    'zone_id'    => (int) $regionZone['zone_id'],
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
