@extends('admin::layouts.master')

@section('title', __('admin/order.list'))

@section('content')
  <div id="customer-app" class="card">
    <div class="card-body">
      <div class="bg-light p-4 mb-3" id="app">
        <el-form :inline="true" :model="filter" class="demo-form-inline" label-width="100px">
          <div>
            <el-form-item label="订单号">
              <el-input v-model="filter.number" size="small" placeholder="订单号"></el-input>
            </el-form-item>
            <el-form-item label="客户姓名">
              <el-input v-model="filter.customer_name" size="small" placeholder="客户姓名"></el-input>
            </el-form-item>
            <el-form-item label="邮箱">
              <el-input v-model="filter.email" size="small" placeholder="邮箱"></el-input>
            </el-form-item>
            <el-form-item label="电话">
              <el-input v-model="filter.telephone" size="small" placeholder="电话"></el-input>
            </el-form-item>
          </div>
          <el-form-item label="订单日期">
            <el-form-item>
              <el-date-picker format="yyyy-MM-dd" value-format="yyyy-MM-dd" type="date" size="small"
                placeholder="选择日期" v-model="filter.start" style="width: 100%;"></el-date-picker>
            </el-form-item>
            <span>-</span>
            <el-form-item>
              <el-date-picker format="yyyy-MM-dd" value-format="yyyy-MM-dd" type="date" size="small"
                placeholder="选择日期" v-model="filter.end" style="width: 100%;"></el-date-picker>
            </el-form-item>
          </el-form-item>
        </el-form>

        <div class="row">
          <label class="wp-100"></label>
          <div class="col-auto">
            <button type="button" @click="search" class="btn btn-outline-primary btn-sm">筛选</button>
            <button type="button" @click="exportOrder" class="btn btn-outline-primary btn-sm ms-1">导出</button>
            <button type="button" @click="resetSearch" class="btn btn-outline-secondary btn-sm ms-1">重置</button>
          </div>
        </div>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>{{ __('order.id') }}</th>
            <th>{{ __('order.number') }}</th>
            <th>{{ __('order.customer_name') }}</th>
            <th>{{ __('order.payment_method') }}</th>
            <th>{{ __('order.status') }}</th>
            <th>{{ __('order.total') }}</th>
            <th>{{ __('order.created_at') }}</th>
            <th>{{ __('order.updated_at') }}</th>
            <th>{{ __('common.action') }}</th>
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
              <td><a href="{{ admin_route('orders.show', [$order->id]) }}" class="btn btn-outline-secondary btn-sm">查看</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      {{ $orders->withQueryString()->links('admin::vendor/pagination/bootstrap-4') }}
    </div>
  </div>
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#app',
      data: {
        url: @json(admin_route('orders.index')),
        exportUrl: @json(admin_route('orders.export')),
        filter: {
          number: bk.getQueryString('number'),
          customer_name: bk.getQueryString('customer_name'),
          email: bk.getQueryString('email'),
          telephone: bk.getQueryString('telephone'),
          start: bk.getQueryString('start'),
          end: bk.getQueryString('end'),
        },
      },

      computed: {
        query() {
          let query = '';
          const filter = Object.keys(this.filter)
            .filter(key => this.filter[key])
            .map(key => key + '=' + this.filter[key])
            .join('&');

          if (filter) {
            query += '?' + filter;
          }

          return query;
        }
      },

      methods: {
        search() {
          location = this.url + this.query
        },

        resetSearch() {
          Object.keys(this.filter).forEach(key => this.filter[key] = '')
          location = this.url + this.query
        },

        exportOrder() {
          location = this.exportUrl + this.query
        },
      }
    });
  </script>
@endpush
