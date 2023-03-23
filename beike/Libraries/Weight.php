<?php
/**
 * Weight.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2023-03-21 16:29:54
 * @modified   2023-03-21 16:29:54
 */

namespace Beike\Libraries;

class Weight
{
    public const WEIGHT_CLASS = [
        'kg' => 0.001,
        'g'  => 1,
        'oz' => 0.035,
        'lb' => 0.0022046,
    ];
    public const DEFAULT_CLASS = 'g';

    public function __construct()
    {
    }

    public static function getWeightUnits() : array
    {
        return array_keys(self::WEIGHT_CLASS);
    }

    public static function convert($weight, $from, $to = '')
    {
        if (!$to) {
            $to = self::DEFAULT_CLASS;
        }
        if (empty($weight)) {
            return 0;
        }
        return $weight * self::WEIGHT_CLASS[$to] / self::WEIGHT_CLASS[$from];
    }
}
