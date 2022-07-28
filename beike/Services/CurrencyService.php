<?php
/**
 * TaxService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-21 16:29:54
 * @modified   2022-07-21 16:29:54
 */

namespace Beike\Services;

use Beike\Models\Address;
use Beike\Models\TaxRate;
use Beike\Models\TaxRule;

class TaxService
{
    private $taxRates = array();
    private static $taxRules;

    const AVAILABLE_TYPES = ['shipping', 'payment', 'store'];

    public function __construct($data = array())
    {
        $countryId = system_setting('base.config_country_id');
        $zoneId = system_setting('base.config_zone_id');

        $shippingAddress = $data['shipping_address'] ?? null;
        $paymentAddress = $data['payment_address'] ?? null;

        if ($shippingAddress) {
            if ($shippingAddress instanceof Address) {
                $this->setShippingAddress($shippingAddress->country_id, $shippingAddress->zone_id);
            } elseif ($shippingAddress instanceof \ArrayObject) {
                $this->setShippingAddress($countryId, $zoneId);
            } else {
                $this->setShippingAddress($shippingAddress['country_id'], $shippingAddress['zone_id']);
            }
        } elseif (system_setting('base.config_tax_default') == 'shipping') {
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
        } elseif (system_setting('base.config_tax_default') == 'payment') {
            $this->setPaymentAddress($countryId, $zoneId);
        }

        $this->setStoreAddress($countryId, $zoneId);
    }

    public static function getInstance($data = array())
    {
        return new self($data);
    }

    private function getTaxRules($type, $countryId, $zoneId)
    {
        if (self::$taxRules !== null && isset(self::$taxRules["$type-$countryId-$zoneId"])) {
            return self::$taxRules["$type-$countryId-$zoneId"];
        }

        $customerGroupId = (int)system_setting('base.config_customer_group_id');
        $sqlBuilder = TaxRule::query()
            ->leftJoin('tax_rate', 'tax_rule.tax_rate_id', '=', 'tax_rate.tax_rate_id')
            ->join('tax_rate_to_customer_group', 'tax_rate.tax_rate_id', '=', 'tax_rate_to_customer_group.tax_rate_id')
            ->leftJoin('zone_to_geo_zone', 'tax_rate.geo_zone_id', '=', 'zone_to_geo_zone.geo_zone_id')
            ->leftJoin('geo_zone', 'tax_rate.geo_zone_id', '=', 'geo_zone.geo_zone_id')
            ->select('tax_rule.*', 'tax_rate.*')
            ->where('tax_rule.based', $type)
            ->where('tax_rate_to_customer_group.customer_group_id', $customerGroupId)
            ->where('zone_to_geo_zone.country_id', $countryId)
            ->where(function ($query) use ($zoneId) {
                $query->where('zone_to_geo_zone.zone_id', '=', 0)
                    ->orWhere('zone_to_geo_zone.zone_id', '=', (int)$zoneId);
            })
            ->orderBy('tax_rule.priority');
        $data = $sqlBuilder->get();
        self::$taxRules["$type-$countryId-$zoneId"] = $data;
        return $data;
    }

    private function setAddress($type, $countryId, $zoneId)
    {
        if (!in_array($type, self::AVAILABLE_TYPES)) {
            throw new \Exception('invalid tax types');
        }

        $data = $this->getTaxRules($type, $countryId, $zoneId);

        foreach ($data as $result) {
            $this->taxRates[$result->tax_class_id][$result->tax_rate_id] = array(
                'tax_rate_id' => $result->tax_rate_id,
                'name' => $result->name,
                'rate' => $result->rate,
                'type' => $result->type,
                'priority' => $result->priority
            );
        }
    }

    public function unsetRates()
    {
        $this->taxRates = array();
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
     * @param $value
     * @param $taxClassId
     * @param bool|true $calculate
     * @return mixed
     */
    public function calculate($value, $taxClassId, bool $calculate = true)
    {
        if ($taxClassId && $calculate) {
            $amount = 0;
            $taxRates = $this->getRates($value, $taxClassId);
            foreach ($taxRates as $taxRate) {
                if ($calculate != 'P' && $calculate != 'F') {
                    $amount += $taxRate['amount'];
                } elseif ($taxRate['type'] == $calculate) {
                    $amount += $taxRate['amount'];
                }
            }
            return $value + $amount;
        } else {
            return $value;
        }
    }

    public function getTax($value, $taxClassId)
    {
        $amount = 0;
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
        } else {
            return false;
        }
    }

    public function getRates($value, $taxClassId)
    {
        $taxRateData = array();

        if (isset($this->taxRates[$taxClassId])) {
            foreach ($this->taxRates[$taxClassId] as $taxRate) {
                if (isset($taxRateData[$taxRate['tax_rate_id']])) {
                    $amount = $taxRateData[$taxRate['tax_rate_id']]['amount'];
                } else {
                    $amount = 0;
                }

                if ($taxRate['type'] == 'F') {
                    $amount += $taxRate['rate'];
                } elseif ($taxRate['type'] == 'P') {
                    $amount += ($value / 100 * $taxRate['rate']);
                }

                $taxRateData[$taxRate['tax_rate_id']] = array(
                    'tax_rate_id' => $taxRate['tax_rate_id'],
                    'name' => $taxRate['name'],
                    'rate' => $taxRate['rate'],
                    'type' => $taxRate['type'],
                    'amount' => $amount
                );
            }
        }

        return $taxRateData;
    }
}
