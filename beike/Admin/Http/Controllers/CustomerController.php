<?php
/**
 * CustomerController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-06-28 16:17:04
 * @modified   2022-06-28 16:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\CustomerRequest;
use Beike\Admin\Http\Resources\CustomerResource;
use Beike\Admin\Services\CustomerService;
use Beike\Models\Customer;
use Beike\Repositories\AddressRepo;
use Beike\Repositories\CountryRepo;
use Beike\Repositories\CustomerGroupRepo;
use Beike\Repositories\CustomerRepo;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected string $defaultRoute = 'customers.index';
    public function index(Request $request)
    {
        $customers = CustomerRepo::list($request->only(['name', 'email', 'status', 'from', 'customer_group_name']));

        $data = [
            'customers' => CustomerResource::collection($customers),
            'customer_groups' => CustomerGroupRepo::list(),
        ];

        return view('admin::pages.customers.index', $data);
    }

    public function store(CustomerRequest $request)
    {
        $data = $request->only(['email', 'name', 'password', 'status', 'customer_group_id']);
        $customer = CustomerService::create($data);

        return json_success('创建成功！', $customer);
    }

    public function edit(Request $request, Customer $customer)
    {
        $addresses = AddressRepo::listByCustomer($customer->id);
        $data = [
            'customer' => $customer,
            'customer_groups' => CustomerGroupRepo::list(),
            'addresses' => CustomerResource::collection($addresses),
            'countries' => CountryRepo::all(),
            'country_id' => setting('country_id'),
            '_redirect' => $this->getRedirect(),
        ];

        return view('admin::pages.customers.form', $data);
    }

    public function update(CustomerRequest $request, int $customerId)
    {
        $data = $request->only(['email', 'name', 'status', 'customer_group_id']);
        if ($request->get('password')) {
            $data['password'] = $request->get('password');
        }
        $customer = CustomerRepo::update($customerId, $data);

        return json_success('创建成功！', $customer);
    }

    public function destroy(Request $request, int $customerId)
    {
        CustomerRepo::delete($customerId);

        return json_success('删除成功！');
    }

    public function restore(Request $request, int $customerId)
    {
        CustomerRepo::restore($customerId);

        return json_success('恢复成功！');
    }
}
