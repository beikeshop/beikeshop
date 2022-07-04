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

use Beike\Admin\Http\Resources\AddressResource;
use Beike\Admin\Services\AddressService;
use Beike\Repositories\AddressRepo;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request, int $customerId)
    {
        $addresses = AddressRepo::listByCustomer($customerId);
        $data = [
            'addresses' => AddressResource::collection($addresses),
        ];

        return $data;
    }

    public function store(Request $request, int $customerId)
    {
        $address = AddressService::addForCustomer($customerId, $request->all());
        return json_success("地址创建成功", $address);
    }

    public function update(Request $request, int $customerId, int $addressId)
    {
        $address = AddressService::update($addressId, $request->all());

        return json_success("地址成功修改", $address);
    }

    public function destroy(Request $request, int $customerId, int $addressId)
    {
        AddressRepo::delete($addressId);

        return json_success("已成功删除");
    }
}
