<?php
/**
 * TaxService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-21 16:29:54
 * @modified   2022-07-21 16:29:54
 */

namespace Beike\Libraries;

use Beike\Models\Address;
use Beike\Models\TaxRate;
use Beike\Models\TaxRule;

class Tax
{
    private array $taxRates = [];

    private static array $taxRules = [];

    public const AVAILABLE_TYPES = ['shipping', 'payment', 'store'];

    public function __construct($data = [])
    {
        $countryId = (int) system_setting('base.country_id');
        $zoneId    = (int) system_setting('base.zone_id');

        $shippingAddress = $data['shipping_address'] ?? null;
        $paymentAddress  = $data['payment_address']  ?? null;

        if ($shippingAddress) {
            if ($shippingAddress instanceof Address) {
                $this->setShippingAddress($shippingAddress->country_id, $shippingAddress->zone_id);
            } elseif ($shippingAddress instanceof \ArrayObject) {
                $this->setShippingAddress($countryId, $zoneId);
            } else {
                $this->setShippingAddress($shippingAddress['country_id'], $shippingAddress['zone_id']);
            }
        } elseif (system_setting('base.tax_address') == 'shipping') {
            $this->setShippingAddress($countryId, $zoneId);
        }

        if ($paymentAddress) {
            if ($paymentAddress instanceof Address) {
                $this->setPaymentAddress($paymentAddress->country_id, $paymentAddress->zone_id);
            } elseif ($paymentAddress instanceof \ArrayObject) {
                $this->setShippingAddress($countryId, $zoneId);
            } else {
                $this->setPaymentAddress($paymentAddress['country_id'], $paymentAddress['zone_id']);
            }
        } elseif (system_setting('base.tax_address') == 'payment') {
            $this->setPaymentAddress($countryId, $zoneId);
        }

        $this->setStoreAddress($countryId, $zoneId);
    }

    public static function getInstance($data = [])
    {
        return new self($data);
    }

    private function getTaxRules($type, $countryId, $zoneId)
    {
        if (self::$taxRules !== null && isset(self::$taxRules["$type-$countryId-$zoneId"])) {
            return self::$taxRules["$type-$countryId-$zoneId"];
        }

        $sqlBuilder = TaxRule::query()
            ->from('tax_rules as rule')
            ->select('rule.*', 'rate.*')
            ->leftJoin('tax_rates as rate', 'rule.tax_rate_id', '=', 'rate.id')
            ->leftJoin('region_zones as rz', 'rate.region_id', '=', 'rz.region_id')
            ->leftJoin('regions as region', 'rate.region_id', '=', 'region.id')
            ->where('rule.based', $type)
            ->where('rz.country_id', $countryId)
            ->where(function ($query) use ($zoneId) {
                $query->where('rz.zone_id', '=', 0)
                    ->orWhere('rz.zone_id', '=', (int) $zoneId);
            })
            ->orderBy('rule.priority');
        $data                                       = $sqlBuilder->get();
        self::$taxRules["$type-$countryId-$zoneId"] = $data;

        return $data;
    }

    private function setAddress($type, $countryId, $zoneId)
    {
        if (! in_array($type, self::AVAILABLE_TYPES)) {
            throw new \Exception('invalid tax types');
        }

        $data = $this->getTaxRules($type, $countryId, $zoneId);

        foreach ($data as $result) {
            $this->taxRates[$result->tax_class_id][$result->tax_rate_id] = [
                'tax_rate_id' => $result->tax_rate_id,
                'name'        => $result->name,
                'rate'        => $result->rate,
                'type'        => $result->type,
                'priority'    => $result->priority,
            ];
        }
    }

    public function unsetRates()
    {
        $this->taxRates = [];
    }

    public function setShippingAddress($countryId, $zoneId)
    {
        $this->setAddress('shipping', $countryId, $zoneId);
    }

    public function setPaymentAddress($countryId, $zoneId)
    {
        $this->setAddress('payment', $countryId, $zoneId);
    }

    public function setStoreAddress($countryId, $zoneId)
    {
        $this->setAddress('store', $countryId, $zoneId);
    }

    /**
     * $tax = \App\Models\Library\Tax::getInstance();
     * $tax->setShippingAddress(1, 0);
     * $tax->calculate(123.45, 9, $tax->config->getValue('config_tax'))
     *
     * @param           $value
     * @param           $taxClassId
     * @param bool|true $calculate
     * @return mixed
     */
    public function calculate($value, $taxClassId, bool $calculate = true)
    {
        if ($taxClassId && $calculate) {
            $amount   = 0;
            $taxRates = $this->getRates($value, $taxClassId);
            foreach ($taxRates as $taxRate) {
                if ($calculate != 'P' && $calculate != 'F') {
                    $amount += $taxRate['amount'];
                } elseif ($taxRate['type'] == $calculate) {
                    $amount += $taxRate['amount'];
                }
            }

            return $value + $amount;
        }

        return $value;

    }

    public function getTax($value, $taxClassId)
    {
        $amount   = 0;
        $taxRates = $this->getRates($value, $taxClassId);
        foreach ($taxRates as $taxRate) {
            $amount += $taxRate['amount'];
        }

        return $amount;
    }

    public function getRateName($taxRateId)
    {
        $taxRate = TaxRate::query()->find($taxRateId);
        if ($taxRate) {
            return $taxRate->name;
        }

        return false;

    }

    public function getRates($value, $taxClassId)
    {
        $taxRateData = [];

        if (isset($this->taxRates[$taxClassId])) {
            foreach ($this->taxRates[$taxClassId] as $taxRate) {
                if (isset($taxRateData[$taxRate['tax_rate_id']])) {
                    $amount = $taxRateData[$taxRate['tax_rate_id']]['amount'];
                } else {
                    $amount = 0;
                }

                if ($taxRate['type'] == 'flat') {
                    $amount += $taxRate['rate'];
                } elseif ($taxRate['type'] == 'percent') {
                    $amount += ($value / 100 * $taxRate['rate']);
                }

                $taxRateData[$taxRate['tax_rate_id']] = [
                    'tax_rate_id' => $taxRate['tax_rate_id'],
                    'name'        => $taxRate['name'],
                    'rate'        => $taxRate['rate'],
                    'type'        => $taxRate['type'],
                    'amount'      => $amount,
                ];
            }
        }

        return $taxRateData;
    }
}
