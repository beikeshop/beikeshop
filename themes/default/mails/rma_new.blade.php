@extends('layout.mail')

@section('content')
  <tbody>
    <tr style="font-weight:300">
      <td style="width:3.2%;max-width:30px;"></td>
      <td style="max-width:480px;text-align:left;">
        <h1 style="font-size: 20px; line-height: 36px; margin: 0px 0px 22px;">
          {{ __('mail.rma_success') }}
        </h1>
        <p style="font-size:14px;color:#333; line-height:24px; margin:0;">
          {{ __('mail.customer_name', ['name' => $rma->customer_name]) }}！
        </p>
        <p style="line-height: 24px; margin: 6px 0px 10px; overflow-wrap: break-word; word-break: break-all;">
          <span style="color: rgb(51, 51, 51); font-size: 14px;">{{ __('mail.rma_success') }}
            <span style="font-weight: bold;">{{ __('mail.not_oneself') }}</span>
          </span>
        </p>

        <table style="width:100%;font-weight:300;margin-top:10px; margin-bottom:10px;border-collapse:collapse;border:1px solid #eee;">
          <thead>
            <tr>
              <td style="font-size:13px;padding: 7px 6px;border: 1px solid #eee;background-color: #f8f9fa;">{{ __('shop/account/rma_form.service_type') }}</td>
              <td style="font-size:13px;padding: 7px 6px;border: 1px solid #eee;background-color: #f8f9fa;">{{ __('shop/account/rma_form.return_quantity') }}</td>
              <td style="font-size:13px;padding: 7px 6px;border: 1px solid #eee;background-color: #f8f9fa;">{{ __('common.status') }}</td>
              <td style="font-size:13px;padding: 7px 6px;border: 1px solid #eee;background-color: #f8f9fa;">{{ __('shop/account/rma_form.unpacked') }}</td>
              <td style="font-size:13px;padding: 7px 6px;border: 1px solid #eee;background-color: #f8f9fa;">{{ __('shop/account/rma.creation_time') }}</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="padding:7px;font-size:13px;border: 1px solid #eee;">{{ $rma['type_format']}}</td>
              <td style="padding:7px;font-size:13px;border: 1px solid #eee;">{{ $rma['quantity'] }}</td>
              <td style="padding:7px;font-size:13px;border: 1px solid #eee;">{{ $rma['status_format'] }}</td>
              <td style="padding:7px;font-size:13px;border: 1px solid #eee;">
                @if ($rma['opened'])
                {{ __('common.yes') }}
                @else
                {{ __('common.no') }}
                @endif
              </td>
              <td style="padding:7px;font-size:13px;border: 1px solid #eee;">{{ $rma['created_at'] }}</td>
            </tr>
          </tbody>
        </table>

        <p style="font-size:14px;color:#333; line-height:24px; margin:0;">
          {{ __('mail.rma_product') }}
        </p>

        @php
            $orderProduct = \Beike\Repositories\OrderProductRepo::find($rma['order_product_id']);
        @endphp
        <table style="width:100%;font-weight:300;margin-top:10px; margin-bottom:20px;border-collapse:collapse;border:1px solid #eee;">
          <tbody>
            <tr>
              <td style="border: 1px solid #eee;padding:4px; background-color: #f8f9fa;font-size:13px;padding: 7px;width: 100%">
                <div style="display: flex; align-items: center;">
                  <div style="width: 70px; height: 70px; flex: 0 0 70px; overflow: hidden;">
                    <img src="{{ image_resize($orderProduct->image, 200, 200) }}" style="max-width: 100%; height: 100%; object-fit: cover;">
                  </div>
                  <div style="margin-left: 10px;">
                    <div style="margin-bottom: 10px; font-size: 12px;">{{ $orderProduct->name }}</div>
                    <div>{{ $orderProduct->price }} x {{ $orderProduct->quantity }}</div>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <table style="width:100%;font-weight:300;margin-top:10px; margin-bottom:10px;border-collapse:collapse;border:1px solid #eee;">
          <tbody>
            <tr>
              <td style="border: 1px solid #eee;padding:4px; background-color: #f8f9fa;font-size:13px;padding: 7px;width: 30%">{{ __('shop/account/rma_form.return_reason') }}</td>
              <td style="border: 1px solid #eee;padding:4px;font-size:13px;padding: 7px">
                @php
                  if ($rma['reason']) {
                      $reason = json_decode($rma['reason']['name'], true)[locale()] ?? '';
                  } else {
                      $reason = '';
                  }
                @endphp
                {{ $reason }}
              </td>
            </tr>
            <tr>
              <td style="border: 1px solid #eee;padding:4px; background-color: #f8f9fa;font-size:13px;padding: 7px;width: 30%">{{ __('common.image') }}</td>
              <td style="border: 1px solid #eee;padding:4px;font-size:13px;padding: 7px">
                @if ($rma['images'] && count($rma['images']) > 0)
                <div style="display: flex; flex-wrap: wrap; align-items: center;">
                  @foreach ($rma['images'] as $image)
                    <a style="display: block; width: 60px; height: 60px; margin-right: 2px; margin-bottom: 2px; border: 1px solid #eee; border-radius: 4px; position: relative; display: flex; align-items: center; justify-content: center;" target="_blank" href="{{ image_origin($image) }}" data-toggle="tooltip" title="{{ __('common.quick_view') }}"><img src="{{ image_resize($image, 200, 200) }}" style="width: 100%; max-height: 100%;"></a>
                  @endforeach
                </div>
              @else
                <div>{{ __('admin/builder.text_no') }}</div>
              @endif
              </td>
            </tr>
            <tr>
              <td style="border: 1px solid #eee;padding:4px; background-color: #f8f9fa;font-size:13px;padding: 7px;width: 30%">{{ __('shop/account/rma_form.remark') }}</td>
              <td style="border: 1px solid #eee;padding:4px;font-size:13px;padding: 7px">{!! $rma['comment'] !!}</td>
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
