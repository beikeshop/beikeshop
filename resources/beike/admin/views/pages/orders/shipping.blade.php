<!DOCTYPE html>
<html dir="{{ current_language() }}" lang="{{ current_language() }}">
<head>
  <meta charset="UTF-8" />
  <title>{{ __("admin/order.pick_list") }}</title>
  <link href="{{ mix('/build/beike/admin/css/bootstrap.css') }}" rel="stylesheet">
</head>
<body>
<div class="container">
  <div id="print-button">
    <style media="print">.printer {display:none;} .btn {display:none;}</style>
    <p style="text-align: right;"><button class="btn btn-primary right" type="button" onclick="window.print()" class="printer">{{ __("admin/order.btn_print") }}</button></p>
  </div>
  @foreach ($orders as $order)
  <div style="page-break-after: always;">
    <h1 style="text-align: center;">{{ $order['store_name'] }} {{ __("admin/order.pick_list") }}</h1>
    <table class="table">
      <tbody>
      <tr>
        <td>
          <b>{{ __("admin/order.shipping_customer_name") }}: </b> {{ $order['shipping_customer_name'] }}<br />
          <b>{{ __("admin/order.telephone") }}: </b> {{ $order['shipping_telephone'] }}<br/>
          <b>{{ __("admin/order.email") }}: </b> {{ $order['email'] }}<br/>
          <b>{{ __("admin/order.shipping_address") }}: </b> {{ $order['shipping_customer_name'] . "(" . $order['shipping_telephone'] . ")". ' ', $order['shipping_address_1'] . ' ' . $order['shipping_address_2'] . ' ' . $order['shipping_city'] . ' ' . $order['shipping_zone'] . ' ' . $order['shipping_country'] }}<br />
        </td>
        <td style="width: 50%;">
          <b>{{ __("admin/order.order_number") }}: </b> {{ $order['number'] }}<br />
          <b>{{ __("admin/order.created_at") }}: </b> {{ $order['created_at'] }}<br />
        </td>
      </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
      <tr>
        <td><b>{{ __("admin/order.index") }}</b></td>
        <td><b>{{ __("admin/order.image") }}</b></td>
        <td><b>{{ __("admin/order.product") }}</b></td>
        <td><b>{{ __("admin/order.sku") }}</b></td>
        <td class="text-right"><b>{{ __("admin/order.quantity") }}</b></td>
        <td class="text-right"><b>{{ __("admin/order.price") }}</b></td>
        <td class="text-right"><b>{{ __("admin/order.total") }}</b></td>
      </tr>
      </thead>
      <tbody>
      @if ($order['order_products'])
      @foreach ($order['order_products'] as $product)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td><img class="img-thumbnail" src="{{ $product['image'] }}" alt=""></td>
        <td>{{ $product['name'] }}</td>
        <td>{{ $product['sku'] }}</td>
        <td class="text-right">{{ $product['quantity'] }}</td>
        <td class="text-right">{{ $product['price'] }}</td>
        <td class="text-right">{{ $product['total_format'] }}</td>
      </tr>
      @endforeach
      @endif
      </tbody>
    </table>
    <table class="table table-tdborder-no">
      <thead style="border-top: 1px solid #ddd;">
      <tr>
        <td><b>{{ __("admin/order.product_total") }}</b>: {{ $order['product_total'] }}</td>
        <td></td>
        <td><b>{{ __("admin/order.order_total") }}</b>: {{ $order['total'] }}</td>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td colspan="3">
          <b>{{ $order['store_name'] }}</b> <br />
          <b>{{ __("admin/order.telephone") }}: </b> {{ $order['shipping_telephone'] }}<br />
          <b>{{ __("admin/order.email") }}: </b> {{ $order['email'] }}<br />
          <b>{{ __("admin/order.website") }}: </b> <a href="{{ $order['website'] }}">{{ $order['website'] }}</a></td>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
  @endforeach
</div>
</body>
</html>
