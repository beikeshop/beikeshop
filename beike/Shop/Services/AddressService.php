<?php
/**
 * AddressService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Sam Chen <sam.chen@opencart.cn>
 * @created    2022-07-04 18:52:57
 * @modified   2022-07-04 18:52:57
 */

namespace Beike\Shop\Services;



use Beike\Repositories\AddressRepo;

class AddressService
{
    public static function create($data)
    {
        $data['customer_id'] = current_customer()->customer_id;
        $address = AddressRepo::create($data);
        return $address;
    }

    public static function update($id, $data)
    {
        $address = AddressRepo::find($id);
        if ($address->customer_id != current_customer()->customer_id) {
            $address;
        }
        return AddressRepo::update($address, $data);
    }
}
