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

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\AddressRequest;
use Beike\Shop\Http\Resources\Account\AddressResource;
use Beike\Repositories\AddressRepo;
use Beike\Shop\Services\AddressService;
use Illuminate\Http\Request;
use Beike\Repositories\CountryRepo;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = AddressRepo::listByCustomer(current_customer());
        $data = [
            'countries' => CountryRepo::all(),
            'addresses' => AddressResource::collection($addresses),
        ];

        return view('account/address', $data);
    }

    public function show(Request $request, $id)
    {
        $address = AddressRepo::find($id);

        return json_success('获取成功', new AddressResource($address));
    }

    public function store(AddressRequest $request)
    {
        $data = $request->only(['name', 'phone', 'country_id', 'zone_id', 'city_id', 'city', 'zipcode', 'address_1', 'address_2', 'default']);
        $address = AddressService::create($data);
        return json_success('创建成功', new AddressResource($address));
    }

    public function update(AddressRequest $request, int $id)
    {
        $data = $request->only(['name', 'phone', 'country_id', 'zone_id', 'city_id', 'city', 'zipcode', 'address_1', 'address_2', 'default']);
        $address = AddressService::update($id, $data);
        return json_success('更新成功', new AddressResource($address));
    }

    public function destroy(Request $request, int $id)
    {
        AddressRepo::delete($id);

        return json_success('删除成功');
    }
}
