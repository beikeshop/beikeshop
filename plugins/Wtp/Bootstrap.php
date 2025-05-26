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

namespace Plugin\Wtp;

use Beike\Models\Plugin;
use Beike\Seller\Repositories\SellerRepo;
use Beike\Services\StateMachineService;
use Illuminate\Support\Str;
use Matrix\Exception;
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
            $data['machines']['paying'][StateMachineService::CANCELLED] = ['updateStatus', 'addHistory', 'notifyUpdateOrder'];

            return $data;
        });
        add_hook_filter('service.state_machine.all_statuses', function ($data) {
            $data[] = [
                'status' => 'paying',
                'name'   => trans('Wtp::common.paying'),
            ];

            return $data;
        });

        add_hook_filter('repo.plugin.payment_methods', function ($data) {
            $paymentSettings = plugin_setting("wtp");
            if (!$data->where('code', 'wtp')->count()) {
                return $data;
            }
            $data      = $data->reject(function ($item) {
                return $item['code'] == 'wtp';
            });

            if (empty($paymentSettings['payment_type'])) {
                return $data;
            }

            $plugin = plugin('wtp');
            foreach ($paymentSettings['payment_type'] as $paymentType) {
                $pluginCopy              = clone $plugin;
                $names                = $pluginCopy->name;

                foreach ($names as $key => $name) {
                    $names[$key] = trans('Wtp::common.' . $paymentType);
                }
                if ($paymentType == 'card') {
                    $pluginCopy->icon = plugin_origin('wtp', '/image/wtp-card.png');
                } else {
                    $pluginCopy->icon = plugin_origin('wtp', '/image/' . $paymentType . '.svg');
                }

                $pluginCopy->setName($names);
                $payment       = new Plugin();
                $payment->id   = 60;
                $payment->type = 'payment';
                $payment->code = 'wtp_' . $paymentType;


                $payment->plugin = $pluginCopy;
                $data->push($payment);
            }


            return $data;
        });

        add_hook_filter('service.payment.pay.view_path', function ($viewPath) {
            if (!Str::startsWith($viewPath, 'Wtp')) {
                return $viewPath;
            }
                $viewPath = "Wtp::" . $result = Str::after($viewPath, '::');
            return $viewPath;
        });
        add_hook_filter('service.payment.pay.data', function ($data) {
            $paymentMethodCode = $data['order']['payment_method_code'];
            if (!Str::startsWith($paymentMethodCode, 'wtp')) {
                return $data;
            }

            $data['payment_type'] = str_replace("wtp_", "", $paymentMethodCode);
            return $data;
        });

    }
}
