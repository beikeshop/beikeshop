@extends('layout.mail')

@section('content')
  <tbody>
    <tr style="font-weight:300">
      <td style="width:3.2%;max-width:30px;"></td>
      <td style="max-width:480px;text-align:left;">
        <h1 style="font-size: 20px; line-height: 36px; margin: 0px 0px 22px;">
          {{ __('mail.welcome_register') }} {{ system_setting('base.meta_title') }}
        </h1>
        <p style="font-size:14px;color:#333; line-height:24px; margin:0;">
          {{ __('mail.customer_name', ['name' => $customer->name]) }}
        </p>
        <p style="line-height: 24px; margin: 6px 0px 0px; overflow-wrap: break-word; word-break: break-all;">
          <span style="color: rgb(51, 51, 51); font-size: 14px;">{{ __('mail.register_end') }}
            <span style="font-weight: bold;">{{ __('mail.not_oneself') }}</span>
          </span>
        </p>
        <p style="font-size: 14px; color: rgb(51, 51, 51); line-height: 24px; margin: 6px 0px 0px; word-wrap: break-word; word-break: break-all;">
          <a href="{{ config('app.url') }}" title="" style="font-size: 16px; line-height: 45px; display: block; background-color: #fd560f; color: rgb(255, 255, 255); text-align: center; text-decoration: none; margin-top: 20px; border-radius: 3px;">
            {{ __('mail.btn_buy_now') }}
          </a>
        </p>
        <dl style="font-size: 14px; color: rgb(51, 51, 51); line-height: 18px;">
          <dd style="margin: 0px 0px 6px; padding: 0px; font-size: 12px; line-height: 22px;">
            <p style="font-size: 14px; line-height: 26px; word-wrap: break-word; word-break: break-all; margin-top: 32px;">
              {{ __('mail.sincerely') }}
              <br>
              <strong>{{ system_setting('base.meta_title') }} {{ __('mail.team') }}</strong>
            </p>
          </dd>
        </dl>
      </td>
      <td style="width:3.2%;max-width:30px;"></td>
    </tr>
  </tbody>
@endsection
