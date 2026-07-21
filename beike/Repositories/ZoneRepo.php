<?php

/**
 * ZoneRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-06-30 15:22:05
 * @modified   2022-06-30 15:22:05
 */

namespace Beike\Repositories;

use Beike\Models\Zone;

class ZoneRepo
{
    /**
     * @param $data
     * @return array
     */
    private static function handleParams($data)
    {
        return [
            'country_id' => $data['country_id']       ?? 0,
            'name'       => $data['name']             ?? '',
            'code'       => $data['code']             ?? '',
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'active'     => (bool) ($data['active']    ?? true),
        ];
    }

    /**
     * 创建一个zone记录
     * @param $data
     * @return mixed
     */
    public static function create($data)
    {
        $data = self::handleParams($data);

        return Zone::query()->create($data);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function update($id, $data)
    {
        $zone = Zone::query()->find($id);
        if (! $zone) {
            throw new \Exception("省份/地区id {$id} 不存在");
        }
        $data = self::handleParams($data);
        $zone->update($data);

        return $zone;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function find($id)
    {
        return Zone::query()->findOrFail($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        $zone = Zone::query()->find($id);
        if ($zone) {
            $zone->delete();
        }
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function list(array $data = [])
    {
        $builder = Zone::query()->with(['country']);

        if (isset($data['name'])) {
            $builder->where('zones.name', 'like', "%{$data['name']}%");
        }
        if (isset($data['code'])) {
            $builder->where('zones.code', 'like', "%{$data['code']}%");
        }
        if (isset($data['active'])) {
            $builder->where('zones.active', $data['active']);
        }
        if (isset($data['country_id'])) {
            $builder->where('zones.country_id', $data['country_id']);
        }

        return $builder->paginate(perPage())->withQueryString();
    }

    /**
     * 根据国家获取国家的省份
     * @param      $country
     * @param bool $onlyActive 是否只获取激活的省份
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|\Illuminate\Support\HigherOrderCollectionProxy|mixed|void
     */
    public static function listByCountry($country, $onlyActive = true)
    {
        if (gettype($country) != 'object') {
            $country = CountryRepo::find($country);
        }
        if ($country) {
            if ($onlyActive) {
                return $country->activeZones;
            }

            return $country->zones;
        }
    }

    /**
     * 通过国家ID获取省份拉下选项
     *
     * @param      $countryId
     * @param bool $onlyActive 是否只获取激活的省份
     * @return array
     */
    public static function getZoneOptions($countryId, $onlyActive = true): array
    {
        $zones = self::listByCountry($countryId, $onlyActive);
        $items = [];
        foreach ($zones as $zone) {
            $items[] = [
                'value' => $zone->id,
                'label' => $zone->name,
            ];
        }

        return $items;
    }
}
