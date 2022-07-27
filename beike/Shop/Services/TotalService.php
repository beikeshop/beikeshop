<?php
/**
 * TotalService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-22 17:11:31
 * @modified   2022-07-22 17:11:31
 */

namespace Beike\Shop\Services;

use Illuminate\Support\Str;

class TotalService
{
    const TOTAL_CODES = [
        'subtotal',
        'tax',
        'shipping',
        'total'
    ];

    public array $carts;
    public array $totals;

    public function __construct($carts)
    {
        $this->carts = $carts;
    }

    /**
     * @return array
     */
    public function getTotals(): array
    {
        $totals = [];
        foreach (self::TOTAL_CODES as $code) {
            $serviceName = Str::studly($code) . 'Service';
            $service = "\Beike\\Shop\\Services\\TotalServices\\{$serviceName}";
            if (!class_exists($service) || !method_exists($service, 'getTotal')) {
                continue;
            }
            $this->totals[] = $service::getTotal($this);
        }

        return $totals;
    }
}
