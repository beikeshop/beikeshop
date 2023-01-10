<?php
/**
 * CustomerGroupService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-01 11:15:25
 * @modified   2022-07-01 11:15:25
 */

namespace Beike\Admin\Services;

use Beike\Repositories\CustomerGroupRepo;

class CustomerGroupService
{
    /**
     * @param $data
     * @return int
     */
    public static function create($data)
    {
        $data          = self::getParams($data);
        $customerGroup = CustomerGroupRepo::create($data);

        $descriptions = [];
        foreach ($data['descriptions'] as $locale => $description) {
            $description['locale'] = $locale;
            $descriptions[]        = $description;
        }
        $customerGroup->descriptions()->createMany($descriptions);

        return $customerGroup;
    }

    public static function update($id, $data)
    {
        $data          = self::getParams($data);
        $customerGroup = CustomerGroupRepo::find($id);

        $customerGroup->update($data);

        $customerGroup->descriptions()->delete();
        $descriptions = [];
        foreach ($data['descriptions'] as $locale => $description) {
            $description['locale'] = $locale;
            $descriptions[]        = $description;
        }
        $customerGroup->descriptions()->createMany($descriptions);

        return $customerGroup;
    }

    private static function getParams($data)
    {
        $descriptions = [];
        foreach ($data['name'] as $locale => $value) {
            $descriptions[$locale] = [
                'name'        => $value,
                'description' => $data['description'][$locale] ?? '',
            ];
        }

        $params = [
            'total'               => (int) $data['total']                 ?? 0,
            'reward_point_factor' => (float) $data['reward_point_factor'] ?? 0,
            'use_point_factor'    => (float) $data['use_point_factor']    ?? 0,
            'discount_factor'     => (float) $data['discount_factor']     ?? 0,
            'level'               => (int) $data['level']                 ?? 0,
            'descriptions'        => $descriptions,
        ];

        return $params;
    }
}
