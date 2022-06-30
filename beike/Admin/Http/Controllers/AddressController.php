<?php
/**
 * AddressController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-06-28 20:17:04
 * @modified   2022-06-28 20:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Resources\CustomerResource;
use Beike\Repositories\AddressRepo;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected string $defaultRoute = 'addresses.index';
    public function index(Request $request, int $customerId)
    {
        $addresses = AddressRepo::listByCustomer($customerId);
        $data = [
            'addresses' => CustomerResource::collection($addresses),
        ];

        return $data;
    }

    public function store(Request $request, int $customerId)
    {
        $data = [
            'name' => $request->get('name', ''),
            'phone' => $request->get('phone', ''),
            'country_id' => (int)$request->get('country_id', 0),
            'zone_id' => (int)$request->get('zone_id', 0),
            'zone' => $request->get('zone', ''),
            'city_id' => (int)$request->get('city_id', 0),
            'city' => $request->get('city', ''),
            'zipcode' => $request->get('zipcode', ''),
            'address_1' => $request->get('address_1', ''),
            'address_2' => $request->get('address_2', ''),
            ];
        $data['customer_id'] = $customerId;
        $address = AddressRepo::create($data);
        return json_success("地址创建成功", $address);
    }

    public function update(Request $request, int $customerId, int $addressId)
    {
        $data = [
            'name' => $request->get('name', ''),
            'phone' => $request->get('phone', ''),
            'country_id' => (int)$request->get('country_id', 0),
            'zone_id' => (int)$request->get('zone_id', 0),
            'zone' => $request->get('zone', ''),
            'city_id' => (int)$request->get('city_id', 0),
            'city' => $request->get('city', ''),
            'zipcode' => $request->get('zipcode', ''),
            'address_1' => $request->get('address_1', ''),
            'address_2' => $request->get('address_2', ''),
        ];
        $address = AddressRepo::update($addressId, $data);

        return json_success("地址成功修改", $address);
    }

    public function destroy(Request $request, int $addressId)
    {
        AddressRepo::delete($addressId);

        return json_success("已成功删除");
    }
}
