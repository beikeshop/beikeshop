@extends('admin::layouts.master')

@section('title', __('admin/plugin.plugin_list'))

@section('body-class', 'page-plugins')

@section('page-title-right')
  <div class="d-flex align-items-center gap-2">
    <!-- Signature Secret Button -->
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
      <div class="plugins-wrap">
        <div v-if="plugins.length">
          <div class="row g-4">
            <div v-for="plugin, index in plugins" :key="index" class="col-6 col-xl-12-15-4 col-xxl-3">
              <div class="card plugin-item overflow-hidden" :data-code="plugin.code">
                <div class="card-body position-relative">
                  <div class="type-format position-absolute font-size-12 text-secondary top-0 end-0 rounded-3 bg-light px-3 py-1">@{{ plugin.type_format }}</div>
                  <div class="d-flex mb-3 align-items-center">
                    <div class="me-2 wh-70 cursor-pointer" style="flex: 0 0 70px;" @click.prevent="showPluginDetail(plugin.code)">
                      <img :src="plugin.icon" class="img-fluid rounded-3">
                    </div>
                    <div class="plugin-describe">
                      <h6 class="plugin-name mb-1">@{{ plugin.name }}</h6>
                      <div class="text-secondary">@{{ plugin.version }}</div>
                    </div>
                  </div>
                  <div class="plugin-description text-secondary" :title="plugin.description" v-html="plugin.description"></div>

                  <div class="d-flex align-items-center justify-content-between mt-4">
                    <div>
                      <div v-if="plugin.installed">
                        <a v-if="plugin.type != 'theme'" class="btn btn-sm btn-outline-secondary" :href="plugin.edit_url">{{ __('admin/common.edit') }}</a>
                        <a v-else :style="!plugin.status ? 'cursor: not-allowed' : ''" :class="['btn btn-sm btn-outline-secondary', !plugin.status ? 'disabled' : '' ]" href="{{ admin_route('theme.index') }}">{{ __('admin/plugin.to_enable') }}</a>
                        <button type="button" v-if="!free_plugin_codes.includes(plugin.code)" class="btn btn-sm btn-outline-secondary" target="_blank" href="javascript:void(0)" @click="toBkTicket(plugin.code)">{{ __('admin/plugin.ticket') }}</button>
                        <a v-if="plugin.can_update" class="btn btn-sm btn-outline-success" @click="updatePlugin(plugin.code, 'install', index)">{{ __('admin/plugin.update') }}</a>
                        <a class="btn btn-outline-danger btn-sm" @click="installedPlugin(plugin.code, 'uninstall', index)">{{ __('admin/common.uninstall') }}</a>
                      </div>
                      <div v-else>
                        <a class="btn btn-outline-success btn-sm" @click="installedPlugin(plugin.code, 'install', index)">{{ __('admin/common.install') }}</a>
                      </div>
                    </div>
                    <div>
                      <el-switch :disabled="!plugin.installed" v-model="plugin.status" @change="(e) => {pluginStatusChange(e, plugin.code, index)}"></el-switch>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-else>
          <x-admin-no-data>
            <x-slot:text>
              {{ __('common.no_data') }} <a href="{{ admin_route('marketing.index', isset($type) ? ['type' => $type]: '') }}"><i class="bi bi-link-45deg"></i> {{ __('common.get_more') }}</a>
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
          $http.get('{{ admin_route('settings.signature_secret_status') }}', null, {hload: true}).then((res) => {
            if (res.data) {
              this.signatureStatus = res.data;
            }
          }).catch((err) => {
            console.error('{{ trans('admin/plugin.load_signature_secret_failed') }}:', err);
          });
        },
        fetchSignatureSecret() {
          this.fetchingSecret = true;
          $http.post('{{ admin_route('settings.fetch_signature_secret') }}').then((res) => {
            this.fetchingSecret = false;
            if (res.data) {
              layer.msg(res.message || '{{ trans('admin/plugin.signature_secret_fetched') }}');
              this.loadSignatureSecretStatus();
            }
          }).catch((err) => {
            this.fetchingSecret = false;
            const message = err.response?.data?.message || err.message || '{{ trans('admin/plugin.signature_secret_fetch_failed') }}';
            layer.msg(message, () => {});
          });
        },
        getSecretRetryButtonText() {
          if (this.fetchingSecret) {
            return '{{ trans('admin/plugin.fetching') }}';
          }
          return '{{ trans('admin/plugin.retry_fetch_signature_secret') }}';
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

        showPluginDetail(code) {
          if (!this.free_plugin_codes.includes(code)) {
            location.href = `{{ admin_route('marketing.index') }}/${code}`;
          }
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
          const lowerMessage = message.toLowerCase();

          // Check for generic server error messages (either in Chinese or English)
          if (!message || lowerMessage.includes('server') || lowerMessage.includes('系统开小差了') || lowerMessage.includes('temporarily unavailable')) {
            return `{{ trans('admin/plugin.plugin_status_update_failed', ['code' => '${code}']) }}`;
          }

          if (message.includes('No query results for model')) {
            return `{{ trans('admin/plugin.plugin_not_found_or_inactive', ['code' => '${code}']) }}`;
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
      },

      mounted() {
        // 高亮并滚动到指定插件
        const urlParams = new URLSearchParams(window.location.search);
        const highlightCode = urlParams.get('highlight');
        if (highlightCode) {
          this.$nextTick(() => {
            const targetRow = $('.plugin-item[data-code=' + highlightCode + ']');
            if (targetRow.length) {
              targetRow.addClass('border-primary overflow-hidden');
              $('#content').animate({scrollTop: targetRow.offset().top - 30}, 500);
              setTimeout(() => {
                targetRow.removeClass('border-primary overflow-hidden');
              }, 3000);
            }
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

    window.addEventListener('message', function (event) {
      if (event.data.type == 'marketing_buy_services_close_pop') {
        layer.closeAll();
        location.reload();
      }
    });
  </script>
@endpush
