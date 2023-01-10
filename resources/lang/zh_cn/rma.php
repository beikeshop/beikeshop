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
    'order_id'         => '订单',
    'order_product_id' => '订单商品',
    'customer_id'      => '顾客',
    'quantity'         => '数量',
    'opened'           => '已拆包装',
    'rma_reason_id'    => '退换货原因',
    'type'             => '售后服务类型',

    'status_pending'   => '待处理',
    'status_rejected'  => '已拒绝',
    'status_approved'  => '已批准（待顾客寄回商品）',
    'status_shipped'   => '已发货（寄回商品）',
    'status_completed' => '已完成',
    'type_return'      => '退货',
    'type_exchange'    => '换货',
    'type_repair'      => '维修',
    'type_reissue'     => '补发商品',
    'type_refund'      => '仅退款',
];
