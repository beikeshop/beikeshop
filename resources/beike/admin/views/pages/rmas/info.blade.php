@extends('admin::layouts.master')

@section('title', __('admin/rma.index'))

@section('content')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">{{ __('admin/rma.rma_details') }}</h6></div>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-4 col-12 order-top-info">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>ID：</td>
                <td>{{ $rma['id'] }}</td>
              </tr>
              <tr>
                  <td>{{ __('admin/rma.customers_name') }}：</td>
                  <td>{{ $rma['name'] }}</td>
              </tr>
              <tr>
                  <td>{{ __('common.phone') }}：</td>
                  <td>{{ $rma['telephone'] }}</td>
              </tr>
              <tr>
                  <td>{{ __('admin/rma.service_type') }}：</td>
                  <td>{{  $rma['type_text'] }}</td>
              </tr>
              <tr>
                  <td>{{ __('admin/rma.order_number') }}：</td>
                  <td><a href="{{ admin_route('orders.show', ['order' => $rma['order_id']]) }}">{{ $rma['order_number'] }}</a></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-lg-4 col-12 order-top-info">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>{{ __('admin/builder.modules_product') }}：</td>
                <td>{{ $rma['product_name'] }}</td>
              </tr>
              <tr>
                <td>{{ __('product.sku') }}：</td>
                <td>{{ $rma['sku'] }}</td>
              </tr>
              <tr>
                <td>{{ __('admin/rma.quantity') }}：</td>
                <td>{{ $rma['quantity'] }}</td>
              </tr>
              <tr>
                <td>{{ __('admin/rma.reasons_return') }}：</td>
                <td>{{ $rma['reason'] }}</td>
              </tr>
              <tr>
                <td>{{ __('common.image') }}：</td>
                @if ($rma['images'] && count($rma['images']) > 0)
                  <td class="d-flex align-items-center flex-wrap">
                    @foreach ($rma['images'] as $image)
                      <a class="img-item wh-60 me-2 mb-2 border rounded position-relative d-flex align-items-center justify-content-center" target="_blank" href="{{ image_origin($image) }}" data-toggle="tooltip" title="{{ __('common.quick_view') }}"><img src="{{ image_resize($image, 200, 200) }}" class="img-fluid"></a>
                    @endforeach
                  </td>
                @else
                  <td>{{ __('admin/builder.text_no') }}</td>
                @endif
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">{{ __('common.status') }}</h6></div>
    <div class="card-body" id="app">
      <el-form ref="form" :model="form" :rules="rules" label-width="140px">
        <el-form-item label="{{ __('admin/rma.current_state') }}">
          {{ $rma['status'] }}
        </el-form-item>
        <el-form-item label="{{ __('admin/rma.modify_status') }}" prop="status">
          <el-select size="small" v-model="form.status" placeholder="{{ __('common.please_choose') }}">
            <el-option
              v-for="item in statuses"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
        {{-- <el-form-item label="通知客户">
          <el-switch v-model="form.notify">
          </el-switch>
        </el-form-item> --}}
        <el-form-item label="{{ __('admin/rma.remarks') }}">
          <textarea class="form-control w-max-500" v-model="form.comment"></textarea>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submitForm('form')">{{ __('admin/rma.update_status') }}</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">{{ __('admin/rma.operation_history') }}</h6></div>
    <div class="card-body">
      <div class="table-push">
        <table class="table ">
          <thead class="">
            <tr>
              <th>{{ __('order.history_status') }}</th>
              <th width="60%">{{ __('order.history_comment') }}</th>
              <th>{{ __('order.history_created_at') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($histories as $history)
              <tr>
                <td>{{ $history['status'] }}</td>
                <td>{{ $history['comment'] }}</td>
                <td>{{ $history['created_at'] }}</td>
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
    new Vue({
      el: '#app',

      data: {
        statuses: [],
        rma: @json($rma ?? []),
        form: {
          status: "",
          notify: false,
          comment: '',
        },

        rules: {
          status: [{required: true, message: '{{ __('common.error_required', ['name' => __('common.status')] ) }}', trigger: 'blur'}, ],
        }
      },

      beforeMount() {
        let statuses = @json($statuses ?? []);
        this.statuses = Object.keys(statuses).map(key => {
          return {
            value: key,
            label: statuses[key]
          }
        });
      },

      methods: {
        submitForm(form) {
          this.$refs[form].validate((valid) => {
            if (!valid) {
              layer.msg('{{ __('common.error_form') }}',()=>{});
              return;
            }

            $http.post(`rmas/history/${this.rma.id}`,this.form).then((res) => {
              layer.msg(res.message, {time: 1000}, ()=> {
                window.location.reload();
              });
            })
          });
        }
      }
    })
  </script>
@endpush

