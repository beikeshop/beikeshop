<?php
/**
 * rma.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-22 19:27:35
 * @modified   2022-08-22 19:27:35
 */

return [
    'order_id'         => '訂單',
    'order_product_id' => '訂單商品',
    'customer_id'      => '顧客',
    'quantity'         => '數量',
    'opened'           => '已拆包裝',
    'rma_reason_id'    => '退換貨原因',
    'type'             => '售後服務類型',

    'status_pending'   => '待處理',
    'status_rejected'  => '已拒絕',
    'status_approved'  => '已批准（待顧客寄回商品）',
    'status_shipped'   => '已發貨（寄回商品）',
    'status_completed' => '已完成',
    'type_return'      => '退貨',
    'type_exchange'    => '換貨',
    'type_repair'      => '維修',
    'type_reissue'     => '補發商品',
    'type_refund'      => '僅退款',
];
