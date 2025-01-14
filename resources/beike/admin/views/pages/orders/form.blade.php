@extends('admin::layouts.master')

@section('title', __('admin/common.order'))

@section('page-bottom-btns')
@hook('order.detail.title.right')
@endsection

@section('page-title-right')
  <a href="{{ admin_route('orders.shipping.get') }}?order_id={{ $order->id }}"target="_blank" class="btn btn-outline-secondary"><i class="bi bi-printer-fill"></i> {{ __('admin/order.btn_print') }}</a>
@endsection

@section('content')
  @hookwrapper('admin.order.form.base')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">{{ __('admin/common.order') }}</h6></div>
    <div class="card-body order-top-info">
      <div class="row">
        <div class="col-lg-4 col-12">
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
                <td>{{ __('admin/plugin.shipping') }}：</td>
                <td>{{ $order->shipping_method_name }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-lg-4 col-12">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>{{ __('order.total') }}：</td>
                <td>{{ currency_format($order->total, $order->currency_code, $order->currency_value) }}</td>
              </tr>
              <tr>
                <td>{{ __('order.customer_name') }}：</td>
                <td>{{ $order->customer_name }}</td>
              </tr>
              <tr>
                <td>{{ __('common.email') }}：</td>
                <td>{{ $order->email }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-lg-4 col-12">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>{{ __('order.created_at') }}：</td>
                <td>{{ $order->created_at }}</td>
              </tr>
              <tr>
                <td>{{ __('order.updated_at') }}：</td>
                <td>{{ $order->updated_at }}</td>
              </tr>
              @hook('admin.order.form.base.updated_at.after')
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  @endhookwrapper

  @hookwrapper('admin.order.form.address')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">{{ __('order.address_info') }}</h6></div>
    <div class="card-body">
      <table class="table table-no-mb">
        <thead class="">
          <tr>
            @if ($order->shipping_country)
            <th>{{ __('order.shipping_address') }}</th>
            @endif
            <th>{{ __('order.payment_address') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            @if ($order->shipping_country)
            <td>
              <div>
                {{ __('address.name') }}：{{ $order->shipping_customer_name }}
                @if ($order->shipping_telephone)
                ({{ $order->shipping_telephone }})
                @endif
              </div>
              <div>
                {{ __('address.address') }}：
                {{ $order->shipping_address_1 }}
                {{ $order->shipping_address_2 }}
                {{ $order->shipping_city }}
                {{ $order->shipping_zone }}
                {{ $order->shipping_country }}
              </div>
              <div>{{ __('address.post_code') }}：{{ $order->shipping_zipcode }}</div>
            </td>
            @endif
            <td>
              <div>
                {{ __('address.name') }}：{{ $order->payment_customer_name }}
                @if ($order->payment_telephone)
                ({{ $order->payment_telephone }})
                @endif
              </div>
              <div>
                {{ __('address.address') }}：
                {{ $order->payment_address_1 }}
                {{ $order->payment_address_2 }}
                {{ $order->payment_city }}
                {{ $order->payment_zone }}
                {{ $order->payment_country }}
              </div>
              <div>{{ __('address.post_code') }}：{{ $order->payment_zipcode }}</div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  @endhookwrapper

  @foreach ($html_items as $item)
    {!! $item !!}
  @endforeach

  @can('orders_update_status')
  @hookwrapper('admin.order.form.status')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">{{ __('order.order_status') }}</h6></div>
    <div class="card-body" id="app">
      <el-form ref="form" :model="form" :rules="rules" label-width="100px">
        <el-form-item label="{{ __('order.current_status') }}">
          {{ $order->status_format }}
        </el-form-item>
        @if (count($statuses))
          <el-form-item label="{{ __('order.change_to_status') }}" prop="status">
            <el-select class="wp-200" size="small" v-model="form.status" placeholder="{{ __('common.please_choose') }}">
              <el-option
                v-for="item in statuses"
                :key="item.status"
                :label="item.name"
                :value="item.status">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="{{ __('order.express_company') }}" v-if="form.status == 'shipped'" prop="express_code">
            <el-select class="wp-200" size="small" v-model="form.express_code" placeholder="{{ __('common.please_choose') }}">
              <el-option
                v-for="item in source.express_company"
                :key="item.code"
                :label="item.name"
                :value="item.code">
              </el-option>
            </el-select>
            <a href="{{ admin_route('settings.index') }}?tab=tab-express-company" target="_blank" class="ms-2">{{ __('common.to_setting') }}</a>
          </el-form-item>
          <el-form-item label="{{ __('order.express_number') }}" v-if="form.status == 'shipped'" prop="express_number">
            <el-input class="w-max-500" v-model="form.express_number" size="small" v-if="form.status == 'shipped'" placeholder="{{ __('order.express_number') }}"></el-input>
          </el-form-item>
          <el-form-item label="{{ __('admin/order.notify') }}">
            <el-checkbox :true-label="1" :false-label="0" v-model="form.notify"></el-checkbox>
          </el-form-item>
          <el-form-item label="{{ __('order.comment') }}">
            <textarea class="form-control w-max-500" v-model="form.comment"></textarea>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitForm('form')">{{ __('order.submit_status') }}</el-button>
          </el-form-item>
        @endif
      </el-form>
    </div>
  </div>
  @endhookwrapper
  @endcan

  @hookwrapper('admin.order.form.products')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">{{ __('order.product_info') }}</h6></div>
    <div class="card-body">
      <div class="table-push">
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
              <td>{{ $product->product_id }}</td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="wh-60 me-2"><img src="{{ image_resize($product->image) }}" class="img-fluid max-h-100"></div>{{ $product->name }}
                  @hook('admin.order_form.product_name.after')
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
  </div>
  @endhookwrapper

  @if ($order->comment)
    <div class="card mb-4">
      <div class="card-header"><h6 class="card-title">{{ __('order.order_comment') }}</h6></div>
      <div class="card-body">{{ $order->comment }}</div>
    </div>
  @endif

  @if ($order->orderPayments)
    @hookwrapper('admin.order.form.payments')
    <div class="card mb-4">
      <div class="card-header"><h6 class="card-title">{{ __('admin/order.payments_history') }}</h6></div>
      <div class="card-body">
        <div class="table-push">
          <table class="table">
            <thead class="">
              <tr>
                <th>{{ __('admin/order.order_id') }}</th>
                <th>{{ __('admin/order.text_transaction_id') }}</th>
                <th>{{ __('admin/order.text_request') }}</th>
                <th>{{ __('admin/order.text_response') }}</th>
                <th>{{ __('admin/order.text_callback') }}</th>
                <th>{{ __('admin/order.text_receipt') }}</th>
                <th>{{ __('order.created_at') }}</th>
                <th>{{ __('order.updated_at') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($order->orderPayments as $payment)
              <tr>
                <td>{{ $payment->order_id }}</td>
                <td>{{ $payment->transaction_id }}</td>
                <td>{{ $payment->request }}</td>
                <td>{{ $payment->response }}</td>
                <td>{{ $payment->callback }}</td>
                <td>
                  @if ($payment->receipt)
                  <a href="{{ image_origin($payment->receipt) }}" target="_blank">{{ __('admin/order.text_click_view') }}</a>
                  @endif
                </td>
                <td>{{ $payment->created_at }}</td>
                <td>{{ $payment->updated_at }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    @endhookwrapper
  @endif

  @if ($order->orderShipments)
    @hookwrapper('admin.order.form.shipments')
    <div class="card mb-4">
      <div class="card-header"><h6 class="card-title">{{ __('order.order_shipments') }}</h6></div>
      <div class="card-body">
        <div class="table-push">
          <table class="table">
            <thead class="">
              <tr>
                <th>{{ __('order.express_company') }}</th>
                <th>{{ __('order.express_number') }}</th>
                <th>{{ __('order.updated_at') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($order->orderShipments as $ship)
              <tr data-id="{{ $ship->id }}">
                <td>
                  <div class="edit-show express-company">{{ $ship->express_company }}</div>
                  @if($expressCompanies)
                  <select class="form-select edit-form express-code d-none" aria-label="Default select example">
                    @foreach ($expressCompanies as $item)
                      <option value="{{ $item['code'] }}" {{ $ship->express_code == $item['code'] ? 'selected' : '' }}>{{ $item['name'] }}</option>
                    @endforeach
                  </select>
                  @endif
                </td>
                <td>
                  <div class="edit-show">{{ $ship->express_number }}</div>
                  <input type="text" class="form-control edit-form express-number d-none" placeholder="{{ __('order.express_number') }}" value="{{ $ship->express_number }}">
                </td>
                <td>
                  <div class="d-flex justify-content-between align-items-center">
                    {{ $ship->created_at }}
                    <div class="btn btn-outline-primary btn-sm edit-shipment">{{ __('common.edit') }}</div>
                    <div class="d-none shipment-tool">
                      <div class="btn btn-primary btn-sm">{{ __('common.confirm') }}</div>
                      <div class="btn btn-outline-secondary btn-sm">{{ __('common.cancel') }}</div>
                    </div>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" class="text-end">
                  <a href="#" class="btn btn-sm btn-outline-secondary add-express">{{ __('admin/order.add_express') }}</a>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
    @endhookwrapper
  @endif

  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">{{ __('order.action_history') }}</h6></div>
    <div class="card-body">
      <div class="table-push">
        <table class="table ">
          <thead class="">
            <tr>
              <th>{{ __('order.history_status') }}</th>
              <th>{{ __('order.history_comment') }}</th>
              <th>{{ __('order.updated_at') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($order->orderHistories as $orderHistory)
              <tr>
                <td>{{ $orderHistory->status_format }}</td>
                <td>{{ $orderHistory->comment }}</td>
                <td>{{ $orderHistory->created_at }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    const express_company = @json(system_setting('base.express_company', []));

    $('.add-express').on('click', function(e) {
      e.preventDefault();

      if (!express_company) {
        layer.alert('{{ __('admin/order.error_no_express_company') }}', {
          title: '{{ __('common.text_hint') }}',
          btn: ['{{ __('admin/order.to_add_express_company') }}'],
          btn1: function(index, layero) {
            window.open('{{ admin_route('settings.index') }}?tab=tab-express-company');
          }
        });
        return;
      }

      let html = '<div class="px-3 pt-3 add-express-wrap">';
      html += '<div class="form-group mb-2">';
      html += '<label for="express_company" class="form-label">{{ __('order.express_company') }}</label>';
      html += '<select class="form-select" id="express_company" aria-label="Default select example">';
      html += '<option value="">{{ __('common.please_choose') }}</option>';
      express_company.forEach(item => {
        html += `<option value="${item.code}">${item.name}</option>`;
      });
      html += '</select>';
      html += '</div>';
      html += '<div class="form-group mb-2">';
      html += '<label for="express_number" class="form-label">{{ __('order.express_number') }}</label>';
      html += '<input type="text" class="form-control" id="express_number" placeholder="{{ __('order.express_number') }}">';
      html += '</div>';
      html += '</div>';

      layer.open({
        type: 1,
        title: '{{ __('admin/order.add_express') }}',
        content: html,
        area: ['400px', '300px'],
        btn: ['{{ __('common.cancel') }}', '{{ __('common.confirm') }}'],
        btn2: function(index, layero) {
          const express_code = $('#express_company').val();
          const express_number = $('#express_number').val();
          if (!express_code) {
            layer.msg('{{ __('common.error_required', ['name' => __('order.express_company')]) }}');
            return false;
          }
          if (!express_number) {
            layer.msg('{{ __('common.error_required', ['name' => __('order.express_number')]) }}');
            return false;
          }

          $http.post(`/orders/{{ $order->id }}/shipments`, {express_code, express_number}).then((res) => {
            layer.msg(res.message);
            window.location.reload();
          });

          return false; // 阻止默认关闭行为
        }
      });
    });
  </script>

@can('orders_update_status')
  <script>
    $('.edit-shipment').click(function() {
      $(this).siblings('.shipment-tool').removeClass('d-none');
      $(this).addClass('d-none');

      $(this).parents('tr').find('.edit-show').addClass('d-none');
      $(this).parents('tr').find('.edit-form').removeClass('d-none');
      @if(!$expressCompanies)
      $(this).parents('tr').find('.express-company').removeClass('d-none');
      @endif
    });

    $('.shipment-tool .btn-outline-secondary').click(function() {
      $(this).parent().siblings('.edit-shipment').removeClass('d-none');
      $(this).parent().addClass('d-none');

      $(this).parents('tr').find('.edit-show').removeClass('d-none');
      $(this).parents('tr').find('.edit-form').addClass('d-none');
    });

    $('.shipment-tool .btn-primary').click(function() {
      const id = $(this).parents('tr').data('id');
      const express_code = $(this).parents('tr').find('.express-code').val();
      const express_name = $(this).parents('tr').find('.express-code option:selected').text();
      const express_number = $(this).parents('tr').find('.express-number').val();

      $(this).parent().siblings('.edit-shipment').removeClass('d-none');
      $(this).parent().addClass('d-none');

      $(this).parents('tr').find('.edit-show').removeClass('d-none');
      $(this).parents('tr').find('.edit-form').addClass('d-none');

      $http.put(`/orders/{{ $order->id }}/shipments/${id}`, {express_code,express_name,express_number}).then((res) => {
        layer.msg(res.message);
        window.location.reload();
      })
    });

    let app = new Vue({
      el: '#app',

      data: {
        statuses: @json($statuses ?? []),
        form: {
          status: "",
          express_number: '',
          express_code: '',
          notify: 0,
          comment: '',
        },

        source: {
          express_company: @json(system_setting('base.express_company', [])) || [],
        },

        rules: {
          status: [{required: true, message: '{{ __('admin/order.error_status') }}', trigger: 'blur'}, ],
          express_code: [{required: true,message: '{{ __('common.error_required', ['name' => __('order.express_company')]) }}',trigger: 'blur'}, ],
          express_number: [{required: true,message: '{{ __('common.error_required', ['name' => __('order.express_number')]) }}',trigger: 'blur'}, ],
        }
      },

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

