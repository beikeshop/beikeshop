@extends('admin::layouts.master')

@section('title', __('admin/plugin.plugin_list'))

@section('page-title-right')
  @hookwrapper('admin.plugin.marketing')
  <a href="{{ admin_route('marketing.index', isset($type) ? ['type' => $type]: '') }}"
     class="btn btn-outline-info">{{ __('common.get_more') }}</a>
  @endhookwrapper
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
                <div class="me-2" style="flex: 0 0 50px;"><img :src="plugin.icon" class="img-fluid border"></div>
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
                  <a class="btn btn-outline-secondary" target="_blank"
                     :href="toBkTicketUrl(plugin.code)">{{ __('admin/plugin.ticket') }}</a>
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

    let app = new Vue({
      el: '#plugins-app',

      data: {
        plugins: plugins,
      },

      beforeMount() {

      },

      methods: {
        toBkTicketUrl(code) {
          return `{{ beike_url() }}/account/plugin_tickets/create?domain=${location.host}&plugin=${code}`
        },

        pluginStatusChange(e, code, index) {
          const self = this;

          $http.put(`plugins/${code}/status`, {status: e * 1}).then((res) => {
            layer.msg(res.message)
          }).catch((res) => {
            this.plugins[index].status = !this.plugins[index].status;
          });
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
          $http.post(`marketing/${code}/download?type=update`, null, {hmsg: true}).then((res) => {
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
        },
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
