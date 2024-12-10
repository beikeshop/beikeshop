<template id="set-token">
  <div>
    <el-dialog
      title="{{ __('admin/marketing.set_token') }}"
      :close-on-click-modal="false"
      :visible.sync="setTokenDialog.show"
      width="500px">
      <el-input
        type="textarea"
        :rows="5"
        placeholder="{{ __('admin/marketing.set_token') }}"
        v-model="setTokenDialog.token">
      </el-input>
      <div class="mt-3 text-secondary fs-6">{{ __('admin/marketing.get_token_text') }} <a href="{{ beike_url() }}/account/websites?domain={{ request()->getHost() }}" class="link-primary" target="_blank">{{ __('admin/marketing.get_token') }}</a></div>
      <div class="d-flex justify-content-end align-items-center mt-4">
        <span slot="footer" class="dialog-footer">
          <el-button @click="setTokenDialog.show = false">{{ __('common.cancel') }}</el-button>
          <el-button type="primary" @click="submitToken">{{ __('common.confirm') }}</el-button>
        </span>
      </div>
    </el-dialog>
  </div>
</template>

@push('footer')
<script>
  Vue.component('v-set-token', {
    template: '#set-token',
    props: {},
    data() {
      return {
        same_domain: @json($same_domain ?? false),
        setTokenDialog: {
          show: false,
          token: @json(system_setting('base.developer_token') ?? ''),
        }
      };
    },

    methods: {
      setToken() {
        if (!this.same_domain) {
          layer.alert('{{ __('admin/marketing.same_domain_error') }}', {icon: 2, area: ['400px'], btn: ['{{ __('common.confirm') }}'], title: '{{__("common.text_hint")}}'});
          return;
        }

        this.setTokenDialog.show = true;
      },

      submitToken() {
        if (!this.setTokenDialog.token) {
          return;
        }

        $http.get(`${config.api_url}/api/website/check_token`, {domain: config.app_url, token: this.setTokenDialog.token}).then((res) => {
          if (!res.exist) {
            layer.msg('{{ __('admin/marketing.check_token_error') }}', () => {});
            return;
          }

          $http.post('{{ admin_route('settings.store_token') }}', {developer_token: this.setTokenDialog.token}).then((res) => {
            layer.msg(res.message)
            window.location.reload();
            this.setTokenDialog.show = false;
          })
        })
      },
    }
  })
</script>
@endpush