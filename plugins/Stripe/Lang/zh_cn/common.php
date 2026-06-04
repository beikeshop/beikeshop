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
    'publishable_key' => '公钥',
    'webhook_secret'  => 'Webhook 密钥',
    'webhook_secret_desc' => <<<HTML
<div class="mt-2 rounded-3 border border-warning-subtle bg-light p-3">
  <div class="fw-bold mb-2">如何获取这个值</div>
  <div class="text-muted mb-2">这里必须填写 Stripe Webhook 的签名密钥，通常以 <code>whsec_</code> 开头。不要填写 API 密钥，例如 <code>sk_test_...</code> 或 <code>sk_live_...</code>。</div>
  <div class="text-muted mb-2">为什么需要这个密钥：BeikeShop 会用它校验 Webhook 回调是否真的来自 Stripe，防止有人伪造支付成功回调，把未支付订单错误标记为已支付。</div>
  <div class="mb-1">具体步骤：</div>
  <ol class="mb-2 ps-3">
    <li>登录 Stripe Dashboard。</li>
    <li>进入 <strong>Developers（开发者）</strong> -> <strong>Webhooks</strong>。</li>
    <li>点击你的 Webhook Endpoint；如果还没有，就先创建一个 Endpoint。</li>
    <li>创建 Endpoint 时选择事件，可以搜索 <code>checkout.session.completed</code>，勾选后继续创建。</li>
    <li>创建完成后，点击刚创建的 Endpoint。</li>
    <li>找到 <strong>Signing secret</strong>。</li>
    <li>点击 <strong>Reveal</strong>（显示）。</li>
    <li>复制显示出来的 Signing Secret，然后粘贴到这里的 <strong>Webhook 密钥</strong> 输入框。</li>
  </ol>
  <div class="text-muted">建议在 Stripe 里把回调地址配置为 <code>https://你的域名/callback/stripe</code>，并确保测试环境和正式环境分别使用各自的 Webhook 密钥。</div>
</div>
HTML,

    'title_info'      => '卡信息',
    'cardnum'         => '卡号',
    'expiration_date' => '截止日期',
    'year'            => '选择年',
    'month'           => '选择月',
    'cvv'             => '安全码',
    'remenber'        => '记住这张卡以便将来使用',
    'btn_submit'      => '提交支付',

    'error_cardnum'   => '请输入卡号',
    'error_cvv'       => '请输入安全码',
    'error_year'      => '请选择年',
    'error_month'     => '请选择月',

    'capture_success' => '支付成功',
    'capture_fail'    => '支付失败',
];
