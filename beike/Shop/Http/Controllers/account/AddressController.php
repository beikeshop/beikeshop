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

use Beike\Shop\Http\Resources\CustomerResource;
use Beike\Repositories\AddressRepo;
use Beike\Repositories\CustomerRepo;
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
        $data = $request->only(['name', 'phone', 'country_id', 'state_id', 'state', 'city_id', 'city', 'zipcode', 'address_1', 'address_2']);
        $data['customer_id'] = $customerId;
        return AddressRepo::create($data);
    }

    public function update(Request $request, int $customerId, int $addressId)
    {
        return AddressRepo::update($addressId, $request->only(['name', 'phone', 'country_id', 'state_id', 'state', 'city_id', 'city', 'zipcode', 'address_1', 'address_2']));
    }

    public function destroy(Request $request, int $customerId)
    {
        CustomerRepo::delete($customerId);

        return ['success' => true];
    }
}
