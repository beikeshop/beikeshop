<?php
/**
 * CustomerDiscountService.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2023-02-07 18:49:15
 * @modified   2023-02-07 18:49:15
 */

namespace Beike\Shop\Services\TotalServices;

use Beike\Shop\Services\CheckoutService;

class CustomerDiscountService
{
    /**
     * @param CheckoutService $checkout
     * @return array
     */
    public static function getTotal(CheckoutService $checkout)
    {
        $totalService = $checkout->totalService;
        $customer     = current_customer();
        if (empty($customer)) {
            return null;
        }

        $discountFactor = $customer->customerGroup->discount_factor;

        $discountFactor = hook_filter('service.total_service.discount_factor', $discountFactor);
        
        if ($discountFactor <= 0) {
            return null;
        }
        if ($discountFactor > 100) {
            $discountFactor = 100;
        }
        $amount       = $totalService->getSubTotal() * $discountFactor / 100;

        $totalData    = [
            'code'          => 'customer_discount',
            'title'         => trans('shop/carts.customer_discount'),
            'amount'        => -$amount,
            'amount_format' => currency_format(-$amount),
        ];

        $totalService->amount += $totalData['amount'];
        $totalService->totals[] = $totalData;

        return $totalData;
    }
}
