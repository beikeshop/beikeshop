@extends('admin::layouts.master')

@section('title', __('admin/order.list'))

@section('content')
  <div id="customer-app" class="card">
    <div class="card-body">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>订单号</th>
            <th>客户姓名</th>
            <th>支付方式</th>
            <th>状态</th>
            <th>总计</th>
            <th>生成日期</th>
            <th>修改日期</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($orders as $order)
            <tr>
              <td>{{ $order->id }}</td>
              <td>{{ $order->number }}</td>
              <td>{{ $order->customer_name }}</td>
              <td>{{ $order->payment_method_name }}</td>
              <td>{{ $order->status }}</td>
              <td>{{ $order->total }}</td>
              <td>{{ $order->created_at }}</td>
              <td>{{ $order->updated_at }}</td>
              <td><a href="{{ admin_route('orders.show', [$order->id]) }}" class="btn btn-outline-secondary btn-sm">查看</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>

      {{ $orders->links('admin::vendor/pagination/bootstrap-4') }}
    </div>
  </div>
@endsection

@push('footer')
  <script>

  </script>
@endpush
