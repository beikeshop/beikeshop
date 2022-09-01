@extends('admin::layouts.master')

@section('title', __('admin/common.order'))

@section('content')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">{{ __('admin/common.order') }}</h6></div>
    <div class="card-body">
      <div class="row">
        <div class="col-4">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>{{ __('order.number') }}：</td>
                <td>{{ $order->number }}</td>
              </tr>
              <tr>
                <td>{{ __('order.payment_method') }}：</td>
                <td>{{ $order->payment_method_name }}</td>
              </tr>
              <tr>
                <td>{{ __('order.total') }}：</td>
                <td>{{ currency_format($order->total, $order->currency_code, $order->currency_value) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-4">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>{{ __('order.customer_name') }}：</td>
                <td>{{ $order->customer_name }}</td>
              </tr>
              <tr>
                <td>{{ __('order.created_at') }}：</td>
                <td>{{ $order->created_at }}</td>
              </tr>
              <tr>
                <td>{{ __('order.updated_at') }}：</td>
                <td>{{ $order->updated_at }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">{{ __('order.address_info') }}</h6></div>
    <div class="card-body">
      <table class="table ">
        <thead class="">
          <tr>
            <th>{{ __('order.shipping_address') }}</th>
            <th>{{ __('order.payment_address') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div>{{ $order->shipping_customer_name }} ({{ $order->shipping_telephone }})</div>
              {{ $order->shipping_country }}
              {{ $order->shipping_zone }}
              {{ $order->shipping_city }}
              {{ $order->shipping_address_1 }}
            </td>
            <td>
              <div>{{ $order->payment_customer_name }} ({{ $order->payment_telephone }})</div>
              {{ $order->payment_country }}
              {{ $order->payment_zone }}
              {{ $order->payment_city }}
              {{ $order->payment_address_1 }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  @can('orders_update_status')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">{{ __('order.order_status') }}</h6></div>
    <div class="card-body" id="app">
      <el-form ref="form" :model="form" :rules="rules" label-width="100px">
        <el-form-item label="{{ __('order.current_status') }}">
          {{ $order->status_format }}
        </el-form-item>
        <el-form-item label="{{ __('order.change_to_status') }}" prop="status">
          <el-select size="small" v-model="form.status" placeholder="{{ __('common.please_choose') }}">
            <el-option
              v-for="item in statuses"
              :key="item.status"
              :label="item.name"
              :value="item.status">
            </el-option>
          </el-select>
        </el-form-item>
        {{-- <el-form-item label="通知客户">
          <el-switch v-model="form.notify">
          </el-switch>
        </el-form-item> --}}
        <el-form-item label="{{ __('order.comment') }}">
          <textarea class="form-control w-max-500" v-model="form.comment"></textarea>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submitForm('form')">{{ __('order.submit_status') }}</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
  @endcan

  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">{{ __('order.product_info') }}</h6></div>
    <div class="card-body">
      <table class="table ">
        <thead class="">
          <tr>
            <th>ID</th>
            <th>{{ __('order.product_name') }}</th>
            <th class="">{{ __('order.product_sku') }}</th>
            <th>{{ __('order.product_price') }}</th>
            <th class="">{{ __('order.product_quantity') }}</th>
            <th class="text-end">{{ __('order.product_sub_price') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($order->orderProducts as $product)
          <tr>
            <td>{{ $product->id }}</td>
            <td>
              <div class="d-flex align-items-center">
                <div class="wh-60 me-2"><img src="{{ $product->image }}" class="img-fluid"></div>{{ $product->name }}
              </div>
            </td>
            <td class="">{{ $product->product_sku }}</td>
            <td>{{ currency_format($product->price, $order->currency_code, $order->currency_value) }}</td>
            <td class="">{{ $product->quantity }}</td>
            <td class="text-end">{{ currency_format($product->price * $product->quantity, $order->currency_code, $order->currency_value) }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          @foreach ($order->orderTotals as $orderTotal)
            <tr>
              <td colspan="5" class="text-end">{{ $orderTotal->title }}</td>
              <td class="text-end"><span class="fw-bold">{{ currency_format($orderTotal->value, $order->currency_code, $order->currency_value) }}</span></td>
            </tr>
          @endforeach
        </tfoot>
      </table>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">{{ __('order.action_history') }}</h6></div>
    <div class="card-body">
      <table class="table ">
        <thead class="">
          <tr>
            <th>{{ __('order.history_status') }}</th>
            <th>{{ __('order.history_comment') }}</th>
            <th>{{ __('order.history_created_at') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($order->orderHistories as $orderHistory)
            <tr>
              <td>{{ $orderHistory->status_format }}</td>
              <td><span class="fw-bold">{{ $orderHistory->comment }}</span></td>
              <td><span class="fw-bold">{{ $orderHistory->created_at }}</span></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

@push('footer')
  @can('orders_update_status')
    <script>
    new Vue({
      el: '#app',

      data: {
        // statuses: [{"value":"pending","label":"待处理"},{"value":"rejected","label":"已拒绝"},{"value":"approved","label":"已批准（待顾客寄回商品）"},{"value":"shipped","label":"已发货（寄回商品）"},{"value":"completed","label":"已完成"}],
        statuses: @json($statuses ?? []),
        form: {
          status: "",
          notify: false,
          comment: '',
        },

        rules: {
          status: [{required: true, message: '{{ __('admin/order.error_status') }}', trigger: 'blur'}, ],
        }
      },

      // beforeMount() {
      //   let statuses = @json($statuses ?? []);
      //   this.statuses = Object.keys(statuses).map(key => {
      //     return {
      //       value: key,
      //       label: statuses[name]
      //     }
      //   });
      // },

      methods: {
        submitForm(form) {
          this.$refs[form].validate((valid) => {
            if (!valid) {
              layer.msg('{{ __('common.error_form') }}',()=>{});
              return;
            }

            $http.put(`/orders/{{ $order->id }}/status`,this.form).then((res) => {
              layer.msg(res.message);
              window.location.reload();
            })
          });
        }
      }
    })
  </script>
  @endcan
@endpush

