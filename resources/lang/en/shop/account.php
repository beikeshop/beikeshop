<?php
/**
 * account.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-04 10:59:15
 * @modified   2022-08-04 10:59:15
 */

return [
    'index' => 'Account',
    'revise_info' => 'Modify Information',
    'collect' => 'Collect',
    'coupon' => 'Coupon',
    'my_order' => 'My Order',
    'orders' => 'All Orders',
    'pending_payment' => 'Pending Payment',
    'pending_send' => 'To be delivered',
    'pending_receipt' => 'Pending Receipt',
    'after_sales' => 'After Sales',
    'no_order' => "You don't have an order yet!",
    'to_buy' => 'To place an order',
    'order_number' => 'Arder Number',
    'order_time' => 'Order Time',
    'state' => 'State',
    'amount' => 'Amount',
    'check_details' => 'Check Details',
    'all' => 'All',
    'items' => 'Items',
    'verify_code_expired' => 'The verify code expired (10 minute), please retry.',
    'verify_code_error' => 'Verify code error!',
    'account_not_exist' => 'Account not exist!',

    'edit' => [
        'index' => 'Edit',
        'modify_avatar' => 'Modify Avatar',
        'suggest' => 'Upload a JPG or PNG image. 300 x 300 is recommended.',
        'name' => 'Name',
        'email' => 'Email',
        'crop' => 'Crop',
        'password_edit_success' => 'Change password successfully!',
        'origin_password_fail' => 'Origin password incorrect!',
    ],

    'wishlist' => [
        'index' => 'Wishlist',
        'product' => 'Product',
        'price' => 'Price',
        'check_details' => 'Check Details',
    ],

    'order' => [
        'index' => 'Order',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'order_details' => 'Order Details',
        'amount' => 'Amount',
        'state' => 'State',
        'order_number' => 'Order Number',
        'check' => 'Check',

        'order_info' => [
            'index' => 'Order Info',
            'order_details' => 'Order Details',
            'to_pay' => 'To Pay',
            'cancel' => 'Cancel',
            'confirm_receipt' => 'Confirm the receipt of goods',
            'order_number' => 'Order Number',
            'order_date' => 'Order Date',
            'state' => 'State',
            'order_amount' => 'Order Amount',
            'order_items' => 'Order Items',
            'apply_after_sales' => 'Apply for after-sales',
            'order_total' => 'Order Total',
            'logistics_status' => 'Logistics Status',
            'order_status' => 'Order Status',
            'remark' => 'Remark',
            'update_time' => 'Update Time',
        ],

        'order_success' => [
            'order_success' => 'Congratulations, the order was successfully generated!',
            'order_number' => 'Order Number',
            'amounts_payable' => 'Amounts Payable ',
            'payment_method' => 'Payment Method ',
            'view_order' => 'View Order ',
            'pay_now' => 'Pay Now ',
            'kind_tips' => 'Reminder: Your order has been successfully generated, please complete the payment as soon as possible~ ',
            'also' => 'You can also',
            'continue_purchase' => 'continue to purchase',
            'contact_customer_service' => 'If you have any questions during the order process, you can contact our customer service staff at any time',
            'emaill' => 'email',
            'service_hotline' => 'Service Hotline',
        ],

    ],

    'addresses' => [
        'index' => 'Addresses',
        'add_address' => 'Add New Address',
        'default_address' => 'Default Address',
        'delete' => 'Delete',
        'edit' => 'Edit',
        'enter_name' => 'Please type in your name',
        'enter_phone' => 'Please type your phone number',
        'enter_address' => 'Please enter detailed address 1',
        'select_province' => 'Please select province',
        'enter_city' => 'Please fill in the city',
        'confirm_delete' => 'Are you sure you want to delete the address?',
        'hint' => 'Hint',
        'check_form' => 'Please check that the form is filled out correctly',
    ],

    'rma' => [
        'index' => 'Rma',
        'commodity' => 'Commodity',
        'quantity' => 'Quantity',
        'service_type' => 'Service Type',
        'return_reason' => 'Reason For Return',
        'creation_time' => 'Creation Time',
        'check' => 'Check',

        'rma_info' => [
            'index' => 'After-sales Details',
        ],

        'rma_form' => [
            'index' => 'Submit after-sales information',
            'service_type' => 'Service Type',
            'return_quantity' => 'Return Quantity',
            'unpacked' => 'Unpacked',
            'return_reason' => 'Reason For Return',
            'remark' => 'Remark',
        ]
    ]
];
