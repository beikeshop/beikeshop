<template id="set-token">
  <div>
    <el-dialog
      title="{{ __('admin/marketing.set_token') }}"
      :close-on-click-modal="false"
      :visible.sync="setTokenDialog.show"
      width="600px">
      <el-input
        type="textarea"
        :rows="5"
        placeholder="{{ __('admin/marketing.set_token') }}"
        v-model="setTokenDialog.token">
      </el-input>
      <div class="mt-3 text-secondary fs-6">{{ __('admin/marketing.get_token_text') }} <a href="javascript:void(0);" class="link-primary" @click="getNewToken">{{ __('admin/marketing.get_token') }}</a></div>
      
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
        },
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

      resolveApiPayload(res) {
        const payload = res && res.data && res.data.data ? res.data.data : (res && res.data ? res.data : res);
        return payload && payload.data ? payload.data : payload;
      },

      resolveTokenValid(res) {
        const payload = this.resolveApiPayload(res);

        if (payload && typeof payload.exist !== 'undefined') {
          return payload.exist;
        }

        if (payload && typeof payload.valid !== 'undefined') {
          return payload.valid;
        }

        if (payload && payload.data && typeof payload.data.exist !== 'undefined') {
          return payload.data.exist;
        }

        if (payload && payload.data && typeof payload.data.valid !== 'undefined') {
          return payload.data.valid;
        }

        return false;
      },

      resolveTokenValue(res) {
        const payload = this.resolveApiPayload(res);

        if (payload && payload.token) {
          return payload.token;
        }

        if (payload && payload.data && payload.data.token) {
          return payload.data.token;
        }

        if (payload && typeof payload.data === 'string') {
          return payload.data;
        }

        if (typeof payload === 'string') {
          return payload;
        }

        return '';
      },

      submitToken() {
        if (!this.setTokenDialog.token) {
          return;
        }

        $http.post('{{ admin_route('settings.store_token') }}', {developer_token: this.setTokenDialog.token}).then((res) => {
          if (res?.data?.developer_token_saved === false) {
            layer.msg(res.message || '开发者令牌校验失败，未保存');
            return;
          }

          layer.msg(res.message || '保存成功');
          window.location.reload();
        }).catch((err) => {
          const message = err?.response?.data?.message || err?.message || '保存失败';
          layer.msg(message, () => {});
        });
      },

      getNewToken() {
        window.open('{{ beike_url() }}/account/websites?domain={{ request()->getHost() }}', '_blank');
        layer.msg('请在官网个人中心复制开发者令牌后粘贴到此处');
      }
    }
  })
</script>
@endpush
