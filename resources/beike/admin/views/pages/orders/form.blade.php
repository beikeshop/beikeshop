@extends('admin::layouts.master')

@section('title', '订单详情')

@section('content')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">订单详情</h6></div>
    <div class="card-body">
      <div class="row">
        <div class="col-4">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>订单编号：</td>
                <td>{{ $order->number }}</td>
              </tr>
              <tr>
                <td>付款方式：</td>
                <td>{{ $order->payment_method_name }}</td>
              </tr>
              <tr>
                <td>总计：</td>
                <td>{{ $order->total }}</td>
              </tr>
              <tr>
                <td>状态：</td>
                <td>{{ $order->status }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-4">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>客户姓名：</td>
                <td>{{ $order->customer_name }}</td>
              </tr>
              <tr>
                <td>生成日期：</td>
                <td>{{ $order->created_at }}</td>
              </tr>
              <tr>
                <td>修改日期：</td>
                <td>{{ $order->updated_at }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">商品信息</h6></div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <td>ID</td>
            <td>商品</td>
            <td>价格</td>
            <td>数量</td>
            <td>sku</td>
          </tr>
        </thead>
        <tbody>
          @foreach ($order->orderProducts as $product)
          <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ $product->product_sku }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          @foreach ($order->orderTotals as $order)
            <tr>
              <td colspan="5" class="text-end">{{ $order->title }}： <span class="fw-bold">{{ $order->value }}</span></td>
              {{-- <td>{{ $order->title }}: {{ $order->value }}</td> --}}
            </tr>
          @endforeach
        </tfoot>
      </table>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">订单操作日志</h6></div>
    <div class="card-body">

    </div>
  </div>
@endsection


