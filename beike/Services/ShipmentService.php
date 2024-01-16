<?php
/**
 * ShipmentService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-12-20 17:23:51
 * @modified   2022-12-20 17:23:51
 */

namespace Beike\Services;

class ShipmentService
{
    /**
     * 处理订单运单数据
     *
     * @param $expressCode
     * @param $expressNumber
     * @return array
     */
    public static function handleShipment($expressCode, $expressNumber): array
    {
        if (empty($expressCode) || empty($expressNumber)) {
            return [];
        }

        $expressCompany = self::handleExpressCompany($expressCode);
        if (empty($expressCompany)) {
            return [];
        }

        return [
            'express_code'    => $expressCode,
            'express_company' => $expressCompany,
            'express_number'  => $expressNumber,
        ];
    }

    /**
     * 根据快递公司编号获取快递公司名称
     *
     * @param $expressCode
     * @return mixed
     */
    public static function handleExpressCompany($expressCode): mixed
    {
        $expressCompanies = system_setting('base.express_company');
        if (empty($expressCompanies)) {
            return '';
        }
        $company = collect($expressCompanies)->where('code', $expressCode)->first();

        return $company ? $company['name'] ?? '' : '';
    }

    /**
     * @param             $orderShipment
     * @param             $data
     * @throws \Throwable
     */
    public static function updateShipment($orderShipment, $data)
    {
        $shipmentData = [
            'express_code'      => $data['express_code']    ?? '',
            'express_company'   => $data['express_name']    ?? '',
            'express_number'    => $data['express_number']  ?? '',
        ];
        $orderShipment->updateOrFail($shipmentData);
    }
}
