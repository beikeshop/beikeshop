<?php
/**
 * OrderPaymentRepo.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-05-25 10:02:39
 * @modified   2023-05-25 10:02:39
 */

namespace Beike\Repositories;

use Beike\Models\OrderPayment;

class OrderPaymentRepo
{
    /**
     * @param $orderId
     * @param $data
     * @return mixed
     * @throws \Throwable
     */
    public static function createOrUpdatePayment($orderId, $data): mixed
    {
        $orderId = (int) $orderId;
        if (empty($orderId) || empty($data)) {
            return null;
        }

        $orderPayment = OrderPayment::query()->where('order_id', $orderId)->first();
        if (empty($orderPayment)) {
            $orderPayment = new OrderPayment();
        }

        $paymentData = [
            'order_id' => $orderId,
        ];

        if (isset($data['transaction_id'])) {
            $paymentData['transaction_id'] = $data['transaction_id'];
        }
        if (isset($data['request'])) {
            $paymentData['request'] = json_encode($data['request'] ?? []);
        }
        if (isset($data['response'])) {
            $paymentData['response'] = json_encode($data['response'] ?? []);
        }
        if (isset($data['callback'])) {
            $paymentData['callback'] = json_encode($data['callback'] ?? []);
        }
        if (isset($data['receipt'])) {
            $paymentData['receipt'] = $data['receipt'];
        }

        $orderPayment->fill($paymentData);
        $orderPayment->saveOrFail();

        return $orderPayment;
    }
}
