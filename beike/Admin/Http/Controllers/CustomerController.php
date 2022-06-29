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

use Beike\Admin\Http\Resources\CustomerResource;
use Beike\Models\Customer;
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
        ];

        return view('admin::pages.customers.index', $data);
    }

    public function create()
    {
        $data = [
            'customer' => new Customer(),
            '_redirect' => $this->getRedirect(),
        ];
        return view('admin::pages.customers.form', $data);
    }

    public function store(Request $request)
    {
        CustomerRepo::create($request->all());

        return redirect($this->getRedirect())->with('success', 'customer created');
    }

    public function edit(Request $request, Customer $customer)
    {
        $data = [
            'customer' => $customer,
            'customer_groups' => CustomerGroupRepo::list(true),
            '_redirect' => $this->getRedirect(),
        ];

        return view('admin::pages.customers.form', $data);
    }

    public function update(Request $request, int $customerId)
    {
        $params = $request->only(['email', 'name', 'status', 'customer_group_id']);
        if ($request->get('password')) {
            $params['password'] = $request->get('password');
        }
        CustomerRepo::update($customerId, $request->all());

        return redirect($this->getRedirect())->with('success', 'customer created');
    }

    public function destroy(Request $request)
    {
        $customerId = $request->id ?? 0;
        CustomerRepo::delete($customerId);

        return ['success' => true];
    }

    public function restore(Request $request)
    {
        $customerId = $request->id ?? 0;
        Customer::withTrashed()->find($customerId)->restore();

        return ['success' => true];
    }
}
