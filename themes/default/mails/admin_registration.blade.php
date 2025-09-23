@extends('layout.mail')

@section('content')
  <tbody>
    <tr style="font-weight:300">
      <td style="width:3.2%;max-width:30px;"></td>
      <td style="max-width:480px;text-align:left;">
        <h1 style="font-size: 20px; line-height: 36px; margin: 0px 0px 22px;">
          {{ __('mail.new_register') }}
        </h1>

        <table style="width:100%;font-weight:300;margin-top:10px; margin-bottom:20px;border-collapse:collapse;border:1px solid #eee;">
          <thead>
            <tr>
              <td style="font-size:13px;padding: 7px 6px;border: 1px solid #eee;background-color: #f8f9fa;">ID</td>
              <td style="font-size:13px;padding: 7px 6px;border: 1px solid #eee;background-color: #f8f9fa;">{{ __('mail.customer_name_line') }}</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="padding:7px;font-size:13px;border: 1px solid #eee; width: 80px;">{{ $customer->id }}</td>
              <td style="padding:7px;font-size:13px;border: 1px solid #eee;">{{ $customer->name }}</td>
            </tr>
          </tbody>
        </table>

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
