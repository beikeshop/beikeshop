@extends('admin::layouts.master')

@section('title', __('admin/order.list'))

@section('content')
  @if ($errors->has('error'))
    <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4" />
  @endif

  @section('page-title-right')
    @if ($type != 'trashed')
      <button type="button" class="btn btn-outline-secondary btn-print" onclick="app.btnPrint()"><i class="bi bi-printer-fill"></i> {{ __('admin/order.btn_print') }}</button>
    @endif
  @endsection

  <div id="orders-app" class="card h-min-600">
    <div class="card-body">
      <div class="bg-light p-4 mb-3">
        <el-form :inline="true" ref="filterForm" :model="filter" class="demo-form-inline" label-width="100px">
          <div>
            <el-form-item label="{{ __('order.number') }}">
              <el-input @keyup.enter.native="search" v-model="filter.number" size="small" placeholder="{{ __('order.number') }}"></el-input>
            </el-form-item>
            <el-form-item label="{{ __('order.customer_name') }}">
              <el-input @keyup.enter.native="search" v-model="filter.customer_name" size="small" placeholder="{{ __('order.customer_name') }}">
              </el-input>
            </el-form-item>
            <el-form-item label="{{ __('order.email') }}">
              <el-input @keyup.enter.native="search" v-model="filter.email" size="small" placeholder="{{ __('order.email') }}"></el-input>
            </el-form-item>
            <el-form-item label="{{ __('common.status') }}" class="el-input--small">
              <select v-model="filter.status" class="form-select wp-100 bg-white bs-el-input-inner-sm">
                <option value="">{{ __('common.all') }}</option>
                @foreach ($statuses as $item)
                  <option value="{{ $item['status'] }}">{{ $item['name'] }}</option>
                @endforeach
              </select>
            </el-form-item>
          </div>
          <el-form-item label="{{ __('order.created_at') }}">
            <el-form-item>
              <el-date-picker format="yyyy-MM-dd" value-format="yyyy-MM-dd" type="date" size="small"
                placeholder="{{ __('common.pick_datetime') }}" @change="pickerDate(1)" v-model="filter.start" style="width: 100%;">
              </el-date-picker>
            </el-form-item>
            <span>-</span>
            <el-form-item>
              <el-date-picker format="yyyy-MM-dd" value-format="yyyy-MM-dd" type="date" size="small"
                placeholder="{{ __('common.pick_datetime') }}" @change="pickerDate(0)" v-model="filter.end" style="width: 100%;">
              </el-date-picker>
            </el-form-item>
          </el-form-item>
        </el-form>

        <div class="row">
          <label class="wp-100"></label>
          <div class="col-auto">
            <button type="button" @click="search"
              class="btn btn-outline-primary btn-sm">{{ __('common.filter') }}</button>
            <button type="button" @click="exportOrder"
              class="btn btn-outline-primary btn-sm ms-1">{{ __('common.export') }}</button>
            <button type="button" @click="resetSearch"
              class="btn btn-outline-secondary btn-sm ms-1">{{ __('common.reset') }}</button>
          </div>
        </div>
      </div>

      @if (count($orders))
        <div class="table-push">
          <table class="table">
            <thead>
              <tr>
                <th><input type="checkbox" v-model="allSelected" /></th>
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
              @if (count($orders))
                @foreach ($orders as $order)
                  <tr data-hook-id={{ $order->id }}>
                    <td><input type="checkbox" :value="{{ $order['id'] }}" v-model="selectedIds" /></td>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->number }}</td>
                    <td>{{ sub_string($order->customer_name, 14) }}</td>
                    <td>{{ $order->payment_method_name }}</td>
                    <td>{{ $order->status_format }}</td>
                    <td>{{ currency_format($order->total, $order->currency_code, $order->currency_value) }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>{{ $order->updated_at }}</td>
                    <td>
                      @if (!$order->deleted_at)
                      <a href="{{ admin_route('orders.show', [$order->id]) }}"
                        class="btn btn-outline-secondary btn-sm">{{ __('common.view') }}
                      </a>
                      <button type="button" data-id="{{ $order->id }}" class="btn btn-outline-danger btn-sm delete-btn">{{ __('common.delete') }}</button>
                      @else
                      <button type="button" data-id="{{ $order->id }}" class="btn btn-outline-secondary btn-sm restore-btn">{{ __('common.restore') }}</button>
                      @hook('admin.products.trashed.action')
                      @endif

                      @hook('admin.order.list.action')
                    </td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="9" class="border-0">
                    <x-admin-no-data />
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
        {{ $orders->withQueryString()->links('admin::vendor/pagination/bootstrap-4') }}
      @else
        <x-admin-no-data />
      @endif
    </div>
  </div>

  @hook('admin.order.list.content.footer')
@endsection

@push('footer')
  <script>
    let app = new Vue({
      el: '#orders-app',
      data: {
        url: '{{ $type == 'trashed' ? admin_route("orders.trashed") : admin_route("orders.index") }}',
        exportUrl: @json(admin_route('orders.export')),
        selectedIds: [],
        orderIds: @json($orders->pluck('id')),
        btnPrintUrl: '',
        filter: {
          number: bk.getQueryString('number'),
          status: bk.getQueryString('status'),
          customer_name: bk.getQueryString('customer_name'),
          email: bk.getQueryString('email'),
          start: bk.getQueryString('start'),
          end: bk.getQueryString('end'),
        },
      },

      watch: {
        "filter.start": {
          handler(newVal,oldVal) {
            if(!newVal) {
              this.filter.start = ''
            }
          }
        },
        "filter.end": {
          handler(newVal,oldVal) {
            if(!newVal) {
              this.filter.end = ''
            }
          }
        },
        "selectedIds": {
          handler(newVal,oldVal) {
            this.btnPrintUrl = `{{ admin_route('orders.shipping.get') }}?selected=${newVal}`;
          }
        }
      },

      computed: {
        allSelected: {
          get(e) {
            return this.selectedIds.length == this.orderIds.length;
          },
          set(val) {
            val ? this.selectedIds = this.orderIds : this.selectedIds = [];
            this.btnPrintUrl = `{{ admin_route('orders.shipping.get') }}?selected=${this.selectedIds}`;
            return val;
          }
        }
      },

      created() {
        bk.addFilterCondition(this);
      },

      methods: {
        btnPrint() {
          if (!this.selectedIds.length) {
            return layer.msg('{{ __('admin/order.order_print_error') }}', ()=>{});
          }
          window.open(this.btnPrintUrl);
        },

        pickerDate(type) {
          if(this.filter.end && this.filter.start > this.filter.end) {
             if(type) {
              this.filter.start = ''
            } else {
              this.filter.end = ''
            }
          }
        },

        search() {
          location = bk.objectToUrlParams(this.filter, this.url)
        },

        resetSearch() {
          this.filter = bk.clearObjectValue(this.filter)
          location = bk.objectToUrlParams(this.filter, this.url)
        },

        exportOrder() {
          location = bk.objectToUrlParams(this.filter, this.exportUrl)
        },
      }
    });
  </script>

<script>
  $('.delete-btn').click(function(event) {
    const id = $(this).data('id');
    const self = $(this);

    layer.confirm('{{ __('common.confirm_delete') }}', {
      title: "{{ __('common.text_hint') }}",
      btn: ['{{ __('common.cancel') }}', '{{ __('common.confirm') }}'],
      area: ['400px'],
      btn2: () => {
        $http.delete(`orders/${id}`).then((res) => {
          layer.msg(res.message);
          window.location.reload();
        })
      }
    })
  });

  $('.restore-btn').click(function(event) {
    const id = $(this).data('id');

    $http.put(`orders/restore/${id}`).then((res) => {
      window.location.reload();
    })
  });
</script>
@endpush
