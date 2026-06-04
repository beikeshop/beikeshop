<?php

/**
 * dd.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-07-28 16:19:06
 * @modified   2022-07-28 16:19:06
 */

return [
    'publishable_key' => 'Publishable Key',
    'webhook_secret'  => 'Webhook Secret',
    'webhook_secret_desc' => <<<HTML
<div class="mt-2 rounded-3 border border-warning-subtle bg-light p-3">
  <div class="fw-bold mb-2">How to get this value</div>
  <div class="text-muted mb-2">This field must use the Stripe webhook signing secret that starts with <code>whsec_</code>. Do not use the API Secret Key such as <code>sk_test_...</code> or <code>sk_live_...</code>.</div>
  <div class="text-muted mb-2">Why this is required: BeikeShop uses this secret to verify that webhook requests really come from Stripe, preventing forged callback requests from marking unpaid orders as paid.</div>
  <div class="mb-1">Steps:</div>
  <ol class="mb-2 ps-3">
    <li>Log in to Stripe Dashboard.</li>
    <li>Open <strong>Developers</strong> -> <strong>Webhooks</strong>.</li>
    <li>Click your Webhook Endpoint. If you do not have one yet, create an Endpoint first.</li>
    <li>When creating the Endpoint, choose the event <code>checkout.session.completed</code>.</li>
    <li>After the Endpoint is created, open it and find <strong>Signing secret</strong>.</li>
    <li>Click <strong>Reveal</strong> to show the secret.</li>
    <li>Copy the displayed Signing Secret and paste it into this Webhook Secret field.</li>
  </ol>
  <div class="text-muted">Recommended callback URL: <code>https://your-domain.com/callback/stripe</code>. Use the webhook secret that matches the current environment endpoint.</div>
</div>
HTML,

    'title_info'      => 'Card information',
    'cardnum'         => 'Cardnum',
    'expiration_date' => 'Expiration Date',
    'year'            => 'Year',
    'month'           => 'Month',
    'cvv'             => 'Cvv',
    'remenber'        => 'Keep this card in mind for future use',
    'btn_submit'      => 'Submit',

    'error_cardnum'   => 'Please enter the card number',
    'error_cvv'       => 'Please enter the security code',
    'error_year'      => 'Please select the year',
    'error_month'     => 'Please select a month',

    'capture_success' => 'Capture Successfully',
    'capture_fail'    => 'Capture Failed',
];
