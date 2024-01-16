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
        'kg' => 1,
        'g'  => 1000,
        'oz' => 35.2739619,
        'lb' => 2.2046226,
        'ct' => 5000,
    ];

    public const DEFAULT_CLASS = 'kg';

    private string $baseWeight;

    public function __construct()
    {
        $this->baseWeight = system_setting('base.weight', self::DEFAULT_CLASS);
    }

    /**
     * @return self
     */
    public static function getInstance()
    {
        return new self;
    }

    public static function getWeightUnits(): array
    {
        return array_keys(self::WEIGHT_CLASS);
    }

    public function convert($weight, $from, $to = ''): float
    {
        if (! $to) {
            $to = $this->baseWeight;
        }
        if (empty($weight)) {
            return 0;
        }

        return (float) ($weight * self::WEIGHT_CLASS[$to] / self::WEIGHT_CLASS[$from]);
    }
}
