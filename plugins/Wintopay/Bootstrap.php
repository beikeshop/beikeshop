<?php
/**
 * bootstrap.php
 *
 * @copyright  2025 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2025-01-03 17:35:59
 * @modified   2025-01-03 17:35:59
 */

namespace Plugin\Wintopay;

use Beike\Seller\Repositories\SellerRepo;
use Beike\Services\StateMachineService;
use Plugin\MultiSeller\Models\Order;
use Plugin\MultiSeller\Services\ShopService;

class Bootstrap
{
    public function boot()
    {
        $this->addEvents();
    }

    private function addEvents()
    {
        // 修改状态机，增加“已拆分”状态，对于主订单，状态变为已付款时不执行任何function
        add_hook_filter('service.state_machine.machines', function ($data) {
            $data['machines'][StateMachineService::UNPAID]['paying'] = ['updateStatus', 'addHistory', 'notifyUpdateOrder'];
            $data['machines']['paying'][StateMachineService::PAID] = ['updateStatus', 'addHistory', 'updateSales', 'subStock', 'notifyUpdateOrder'];

            return $data;
        });
        add_hook_filter('service.state_machine.all_statuses', function ($data) {
            $data[] = [
                'status' => 'paying',
                'name'   => trans('Wintopay::common.paying'),
            ];

            return $data;
        });

    }
}
