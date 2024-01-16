<?php
/**
 * ProcessOrder.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-07-20 16:30:20
 * @modified   2023-07-20 16:30:20
 */

namespace Beike\Console\Commands;

use Beike\Models\OrderHistory;
use Beike\Repositories\OrderRepo;
use Beike\Services\StateMachineService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class ProcessOrder extends Command
{
    protected $signature = 'process:order';

    protected $description = '超时自动取消未支付订单, 超时自动完成已发货订单';

    protected LoggerInterface $logger;

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $this->logger = $this->buildLogger();
        $this->cancelOrders();
        $this->completeOrders();
    }

    /**
     * 自动取消未支付订单
     *
     * @throws \Exception
     */
    private function cancelOrders()
    {
        $this->logInfo('====== 自动取消未支付订单 ======');

        $cancelHours = (int) system_setting('base.order_auto_cancel');
        if ($cancelHours) {
            $minDatetime = now()->subHours($cancelHours);
        } else {
            $minDatetime = now()->subWeek();
        }

        $this->logInfo("Min Datetime: $minDatetime");

        $unpaidOrders = OrderRepo::getListBuilder(['status' => StateMachineService::UNPAID])
            ->where('created_at', '<', $minDatetime)
            ->orderBy('created_at')
            ->get();

        $orderTotal = $unpaidOrders->count();
        foreach ($unpaidOrders as $index => $order) {
            $count = $index + 1;
            $this->logInfo("处理 $count/$orderTotal: ID:$order->id - Number: $order->number - $order->created_at");
            (new StateMachineService($order))->changeStatus(StateMachineService::CANCELLED);
        }
    }

    /**
     * 自动完成已发货订单
     *
     * @throws \Exception
     */
    private function completeOrders()
    {
        $this->logInfo('====== 自动完成已发货订单 ======');

        $completeHours = (int) system_setting('base.order_auto_complete');
        if ($completeHours) {
            $minDatetime = now()->subHours($completeHours);
        } else {
            $minDatetime = now()->subWeek();
        }

        $this->logInfo("Min Datetime: $minDatetime");

        $unpaidOrders = OrderRepo::getListBuilder(['status' => StateMachineService::SHIPPED])
            ->orderBy('created_at')
            ->get();

        $orderTotal = $unpaidOrders->count();
        foreach ($unpaidOrders as $index => $order) {
            $count = $index + 1;
            $this->logInfo("处理 $count/$orderTotal: ID:$order->id - Number: $order->number - $order->created_at");

            $shipHistory = OrderHistory::query()->where('order_id', $order->id)->orderByDesc('id')->first();
            if (empty($shipHistory)) {
                continue;
            }
            $shipDatetime = $shipHistory->created_at;

            $this->logInfo("Shipped Datetime: $shipDatetime");
            if ($shipDatetime < $minDatetime) {
                (new StateMachineService($order))->changeStatus(StateMachineService::COMPLETED);
            }
        }
    }

    /**
     * Build current logger
     *
     * @return LoggerInterface
     */
    private function buildLogger(): LoggerInterface
    {
        return Log::build([
            'driver' => 'single',
            'path'   => storage_path('logs/process_order.log'),
        ]);
    }

    /**
     * Log information
     *
     * @param ...$messages
     */
    private function logInfo(...$messages)
    {
        foreach ($messages as $message) {
            if (PHP_SAPI == 'cli') {
                dump($message);
            }
            $this->logger->info($message);
        }
    }
}
