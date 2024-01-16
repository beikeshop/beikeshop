<?php
/**
 * AddressService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-04 18:52:57
 * @modified   2022-07-04 18:52:57
 */

namespace Beike\Shop\Services;

use Beike\Repositories\AddressRepo;
use Beike\Repositories\ZoneRepo;

class AddressService
{
    public static function create($data)
    {
        $customer            = current_customer();
        $data['customer_id'] = $customer->id;
        $data['zone']        = ZoneRepo::find($data['zone_id'])->name;
        $address             = AddressRepo::create($data);

        if ($data['default']) {
            $customer->address_id = $address->id;
            $customer->save();
        }

        return $address;
    }

    public static function update($id, $data)
    {
        $customer = current_customer();
        $address  = AddressRepo::find($id);
        if ($address->customer_id != $customer->id) {
            return null;
        }
        $data['zone'] = ZoneRepo::find($data['zone_id'])->name;
        $address      = AddressRepo::update($address, $data);
        if ($data['default'] && $customer->address_id != $address->id) {
            $customer->address_id = $address->id;
            $customer->save();
        }

        return $address;
    }
}
