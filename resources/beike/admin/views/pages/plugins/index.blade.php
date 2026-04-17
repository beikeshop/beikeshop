@extends('admin::layouts.master')

@section('title', __('admin/plugin.plugin_list'))

@section('page-title-right')
  <div class="d-flex align-items-center gap-2">
    <!-- 签名密钥按钮 -->
    <div id="signature-secret-app" v-cloak style="display: none;">
      <el-button 
        v-if="!signatureStatus.has_secret"
        size="small" 
        type="warning"
        :loading="fetchingSecret"
        @click="fetchSignatureSecret">
        <i class="bi bi-key me-1"></i>
        @{{ getSecretRetryButtonText() }}
      </el-button>
    </div>
    
    @hookwrapper('admin.plugin.marketing')
    <a href="{{ admin_route('marketing.index', isset($type) ? ['type' => $type]: '') }}"
       class="btn btn-outline-info">{{ __('common.get_more') }}</a>
    @endhookwrapper
  </div>
@endsection

@section('page-title-after')
<div class="position-relative w-min-300">
  <div class="position-absolute top-50 start-0 translate-middle-y" style="padding-left: 14px;"><i class="bi bi-search"></i></div>
  <input type="text" class="form-control search-plugin-input" style="padding-left: 36px;" placeholder="{{ __('admin/builder.text_search') }}">
</div>
@endsection

@section('content')
  <div id="plugins-app" class="card" v-cloak>
    <div class="card-body h-min-600">
      <div class="mt-4 table-push" style="">
        <table class="table" v-if="plugins.length">
          <thead>
          <tr>
            <th>{{ __('admin/plugin.plugin_code') }}</th>
            <th>{{ __('admin/plugin.plugin_version') }}</th>
            <th>{{ __('admin/plugin.plugin_type') }}</th>
            <th width="50%">{{ __('admin/plugin.plugin_description') }}</th>
            <th>{{ __('admin/common.status') }}</th>
            <th>{{ __('admin/common.action') }}</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="plugin, index in plugins" :key="index">
            <td>@{{ plugin.code }}</td>
            <td>@{{ plugin.version }}</td>
            <td>@{{ plugin.type_format }}</td>
            <td>
              <div class="plugin-describe d-flex align-items-center">
                <div class="me-2 cursor-pointer" style="flex: 0 0 50px;" @click.prevent="showPluginDetail(plugin.code)"><img :src="plugin.icon" class="img-fluid border"></div>
                <div>
                  <h6 class="plugin-name">@{{ plugin.name }}</h6>
                  <div class="plugin-description" v-html="plugin.description"></div>
                </div>
              </div>
            </td>
            <td>
              <el-switch :disabled="!plugin.installed" v-model="plugin.status"
                         @change="(e) => {pluginStatusChange(e, plugin.code, index)}"></el-switch>
            </td>
            <td>
              <div v-if="plugin.installed">
                <div class="btn-group btn-group-sm">
                  <a v-if="plugin.type != 'theme'" class="btn btn-outline-secondary"
                     :href="plugin.edit_url">{{ __('admin/common.edit') }}</a>
                  <a v-else :style="!plugin.status ? 'cursor: not-allowed' : ''"
                      :class="['btn btn-outline-secondary', !plugin.status ? 'disabled' : '' ]"
                      href="{{ admin_route('theme.index') }}">{{ __('admin/plugin.to_enable') }}</a>
                  <button type="button" v-if="!free_plugin_codes.includes(plugin.code)" class="btn btn-sm btn-outline-secondary" target="_blank" href="javascript:void(0)" @click="toBkTicket(plugin.code)">{{ __('admin/plugin.ticket') }}</button>
                  <a v-if="plugin.can_update" class="btn btn-outline-success"
                  @click="updatePlugin(plugin.code, 'install', index)">{{ __('admin/plugin.update') }}</a>
                </div>
                <a class="btn btn-outline-danger btn-sm"
                    @click="installedPlugin(plugin.code, 'uninstall', index)">{{ __('admin/common.uninstall') }}</a>
              </div>
              <div v-else>
                <a class="btn btn-outline-success btn-sm"
                   @click="installedPlugin(plugin.code, 'install', index)">{{ __('admin/common.install') }}</a>
              </div>
            </td>
          </tr>
          </tbody>
        </table>
        <div v-else>
          <x-admin-no-data>
            <x-slot:text>
              {{ __('common.no_data') }} <a
                href="{{ admin_route('marketing.index', isset($type) ? ['type' => $type]: '') }}"><i
                  class="bi bi-link-45deg"></i> {{ __('common.get_more') }}</a>
            </x-slot>
          </x-admin-no-data>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    const plugins = @json($plugins ?? []);

    // 签名密钥状态管理
    let signatureSecretApp = new Vue({
      el: '#signature-secret-app',
      data: {
        signatureStatus: {
          has_secret: false,
          secret_length: 0,
          secret_preview: '',
        },
        fetchingSecret: false,
      },
      mounted() {
        this.loadSignatureSecretStatus();
      },
      methods: {
        loadSignatureSecretStatus() {
          $http.get('{{ admin_route('settings.signature_secret_status') }}').then((res) => {
            if (res.data) {
              this.signatureStatus = res.data;
            }
          }).catch((err) => {
            console.error('加载签名密钥状态失败:', err);
          });
        },
        fetchSignatureSecret() {
          this.fetchingSecret = true;
          $http.post('{{ admin_route('settings.fetch_signature_secret') }}').then((res) => {
            this.fetchingSecret = false;
            if (res.data) {
              layer.msg(res.message || '签名密钥获取成功');
              this.loadSignatureSecretStatus();
            }
          }).catch((err) => {
            this.fetchingSecret = false;
            const message = err.response?.data?.message || err.message || '获取签名密钥失败';
            layer.msg(message, () => {});
          });
        },
        getSecretRetryButtonText() {
          if (this.fetchingSecret) {
            return '重试中...';
          }
          return '重试获取签名密钥（高级）';
        }
      }
    });

    let app = new Vue({
      el: '#plugins-app',

      data: {
        plugins: plugins,
        free_plugin_codes: @json(config('app.free_plugin_codes')),
        statusLoading: {},
        statusRollbackGuard: {},
      },

      beforeMount() {

      },

      methods: {
        toBkTicket(code) {
          this.pluginServiceExpired(code).then(() => {
            window.open(`{{ beike_url() }}/account/plugin_tickets/create?domain=${location.host}&plugin=${code}`);
          })
        },

        pluginStatusChange(e, code, index) {
          if (this.statusRollbackGuard[code]) {
            this.$delete(this.statusRollbackGuard, code);
            return;
          }

          if (this.statusLoading[code]) {
            return;
          }

          this.$set(this.statusLoading, code, true);

          $http.put(`plugins/${code}/status`, {status: e * 1}).then((res) => {
            layer.msg(res.message || '{{ __("common.updated_success") }}');
          }).catch((err) => {
            this.$set(this.statusRollbackGuard, code, true);

            if (this.plugins[index]) {
              this.plugins[index].status = Number(!e);
            }

            layer.msg(this.resolveApiErrorMessage(err, code));
          }).finally(() => {
            this.$set(this.statusLoading, code, false);
          });
        },

        resolveApiErrorMessage(err, code) {
          const message = err?.response?.data?.message || err?.message || '';

          if (!message || message.includes('系统开小差了')) {
            return `插件“${code}”状态更新失败：插件市场服务异常，请稍后重试。`;
          }

          if (message.includes('No query results for model')) {
            return `插件“${code}”在插件市场不存在或未激活，请先在 API 端检查插件记录。`;
          }

          return message;
        },

        installedPlugin(code, type, index) {
          if (type == 'uninstall') {
            layer.confirm('{{ __('admin/plugin.uninstall_hint') }}', {
              title: "{{ __('common.text_hint') }}",
              btn: ['{{ __('common.cancel') }}', '{{ __('common.confirm') }}'],
              area: ['400px'],
              btn2: () => {
                this.installedPluginXhr(code, type, index);
              }
            })
            return;
          }

          this.installedPluginXhr(code, type, index);
        },

        installedPluginXhr(code, type, index) {
          $http.post(`plugins/${code}/${type}`).then((res) => {
            layer.msg(res.message)
            this.plugins[index].installed = type == 'install' ? true : false;
          })
        },

        updatePlugin(code) {
          this.pluginServiceExpired(code).then(() => {
            $http.post(`marketing/${code}/download?type=update`, null).then((res) => {
              layer.msg(res.message);
              if (res.status == 'success') {
                location.reload(true);
              }
            }).catch((err) => {
              if (err.response.data.message == 'plugin_pending') {
                layer.alert('{{__('admin/marketing.pluginstatus_pending')}}', {
                  btn: ['{{ __('common.confirm') }}'],
                  title: '{{__("common.text_hint")}}'
                });
              } else if (err.response.data.message == 'Not a zip archive') {
                layer.alert('{{ __('admin/marketing.not_zip_archive') }}', {
                  icon: 2,
                  area: ['400px'],
                  btn: ['{{ __('common.confirm') }}'],
                  title: '{{__("common.text_hint")}}'
                });
              } else {
                layer.msg(err.response.data.message || err.message, {time: 3000}, () => {
                });
              }
            })
          })
        },

        showPluginDetail(code) {
          if (!this.free_plugin_codes.includes(code)) {
            location.href = `{{ admin_route('marketing.index') }}/${code}`;
          }
        },

        pluginServiceExpired(code) {
          return new Promise((resolve, reject) => {
            $http.get('{{ admin_route('plugins.ticket_expired') }}', { plugin_code: code }).then(res => {
              if (res.data.expired) {
                layer.open({
                  type: 2,
                  title: '{{ __('admin/plugin.ticket') }}',
                  area: ['600px', '520px'],
                  content: '{{ admin_route('marketing.index') }}/' + code + '?buy_service=1',
                });
              } else {
                resolve(res.data);
              }
            })
          });
        }
      }
    })

    function debounce(fn, delay) {
      let timer;
      return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), delay);
      };
    }

    const filterPlugins = debounce(function () {
      const keyword = $('.search-plugin-input').val().toLowerCase();

      if (!keyword) {
        app.plugins = plugins;
        return;
      }

      app.plugins = plugins.filter(plugin =>
        plugin.name.toLowerCase().includes(keyword) ||
        plugin.description.toLowerCase().includes(keyword)
      );
    }, 300);

    $('.search-plugin-input').on('input', filterPlugins);
  </script>
@endpush
