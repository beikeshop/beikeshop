@extends('admin::layouts.master')

@section('title', __('admin/marketing.marketing_show'))

@section('body-class', 'page-marketing-info')

@section('content')
  @php
    $data = $plugin['data'];
  @endphp
  <div class="card mb-4" id="app">
    <div class="card-body">
      <div class="d-flex mb-5">
        <div class="border wp-400 hp-400 d-flex justify-content-between align-items-center"><img src="{{ $data['icon_big'] }}" class="img-fluid"></div>
        <div class="ms-5 mt-2">
          <h3 class="card-title mb-4">{{ $data['name'] }}</h3>
          <div class="plugin-item d-flex align-items-center mb-4 lh-1 text-secondary">
            <div class="mx-3 ms-0">{{ __('admin/marketing.download_count') }}：{{ $data['downloaded'] }}</div><span class="vr lh-1 bg-secondary"></span>
            <div class="mx-3">{{ __('admin/marketing.last_update') }}：{{ $data['updated_at'] }}</div><span class="vr lh-1 bg-secondary"></span>
            <div class="mx-3">{{ __('admin/marketing.text_version') }}：{{ $data['version'] }}</div>
          </div>

          <div class="mb-4">
            <div class="mb-2 fw-bold">{{ __('admin/marketing.text_compatibility') }}：</div>
            <div>{{ $data['version_name_format'] }}</div>
          </div>
          <div class="mb-5">
            <div class="mb-2 fw-bold">{{ __('admin/marketing.text_author') }}：</div>
            <div class="d-flex">
              <div class="border wh-60 d-flex justify-content-between align-items-center"><img src="{{ $data['developer']['avatar'] }}" class="img-fluid"></div>
              <div class="ms-3">
                <div class="mb-2 fw-bold">{{ $data['developer']['name'] }}</div>
                <div>{{ $data['developer']['email'] }}</div>
              </div>
            </div>
          </div>

          <div>
            <button class="btn btn-primary btn-lg" @click="downloadPlugin"><i class="bi bi-cloud-arrow-down-fill"></i> {{ __('admin/marketing.download_plugin') }}</button>
            <div class="mt-3 d-none download-help"><a href="{{ admin_route('plugins.index') }}" class=""><i class="bi bi-cursor-fill"></i> <span></span></a></div>
          </div>
        </div>
      </div>

      <div>
        <h5 class="text-center">{{ __('admin/marketing.download_description') }}</h5>
        <div>{{ $data['description'] }}</div>
      </div>
    </div>

    <el-dialog
      title="{{ __('admin/marketing.set_token') }}"
      :close-on-click-modal="false"
      :visible.sync="setTokenDialog.show"
      width="500px">
      <el-input
        type="textarea"
        :rows="4"
        placeholder="{{ __('admin/marketing.set_token') }}"
        v-model="setTokenDialog.token">
      </el-input>
      <span slot="footer" class="dialog-footer">
        <el-button @click="setTokenDialog.show = false">{{ __('common.cancel') }}</el-button>
        <el-button type="primary" @click="submitToken">{{ __('common.confirm') }}</el-button>
      </span>
    </el-dialog>
  </div>
@endsection

@push('footer')
  <script>
    let app = new Vue({
      el: '#app',

      data: {
        setTokenDialog: {
          show: false,
          token: @json(system_setting('base.developer_token') ?? ''),
        }
      },

      methods: {
        downloadPlugin() {
          if (!this.setTokenDialog.token) {
            return this.setTokenDialog.show = true;
          }

          $http.post('{{ admin_route('marketing.download', ['code' => $data['code']]) }}').then((res) => {
            $('.download-help').removeClass('d-none').find('span').text(res.message);
          })
        },

        submitToken() {
          if (!this.setTokenDialog.token) {
            return;
          }

          $http.post('{{ admin_route('settings.store_token') }}', {developer_token: this.setTokenDialog.token}).then((res) => {
            this.setTokenDialog.show = false;
            layer.msg(res.message);
          })
        }
      }
    })
  </script>
@endpush
