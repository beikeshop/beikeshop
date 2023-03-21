@extends('admin::layouts.master')

@section('title', __('admin/plugin.plugin_list'))

@section('page-title-right')
  @hookwrapper('admin.plugin.marketing')
  <a href="{{ admin_route('marketing.index') }}" class="btn btn-outline-info">{{ __('admin/marketing.marketing_list') }}</a>
  @endhookwrapper
@endsection

@section('content')

  <div id="plugins-app" class="card" v-cloak>
    <div class="card-body h-min-600">
      <div class="mt-4 table-push" style="">
        <table class="table">
          <thead>
            <tr>
              <th>{{ __('admin/plugin.plugin_code') }}</th>
              <th>{{ __('admin/plugin.plugin_type') }}</th>
              <th width="50%">{{ __('admin/plugin.plugin_description') }}</th>
              <th>{{ __('admin/common.status') }}</th>
              <th>{{ __('admin/common.action') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="plugin, index in plugins" :key="index" v-if="plugins.length">
              <td>@{{ plugin.code }}</td>
              <td>@{{ plugin.type_format }}</td>
              <td>
                <div class="plugin-describe d-flex align-items-center">
                  <div class="me-2" style="flex: 0 0 50px;"><img :src="plugin.icon" class="img-fluid border"></div>
                  <div>
                    <h6>@{{ plugin.name }}</h6>
                    <div class="" v-html="plugin.description"></div>
                  </div>
                </div>
              </td>
              <td>
                <el-switch :disabled="!plugin.installed" v-model="plugin.status" @change="(e) => {pluginStatusChange(e, plugin.code, index)}"></el-switch>
              </td>
              <td>
                <div v-if="plugin.installed">
                  <span :style="!plugin.status ? 'cursor: not-allowed':''">
                    <a v-if="plugin.type != 'theme'" :class="['btn btn-outline-secondary btn-sm', !plugin.status ? 'disabled' : '' ]" :href="plugin.edit_url">{{ __('admin/common.edit') }}</a>
                    <a v-else :class="['btn btn-outline-secondary btn-sm', !plugin.status ? 'disabled' : '' ]" href="{{ admin_route('theme.index') }}">{{ __('admin/plugin.to_enable') }}</a>
                  </span>
                  <a class="btn btn-outline-danger btn-sm" @click="installedPlugin(plugin.code, 'uninstall', index)">{{ __('admin/common.uninstall') }}</a>
                </div>
                <div v-else>
                  <a class="btn btn-outline-success btn-sm" @click="installedPlugin(plugin.code, 'install', index)">{{ __('admin/common.install') }}</a>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#plugins-app',

      data: {
        plugins: @json($plugins ?? []),
      },

      beforeMount() {

      },

      methods: {
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
        }
      }
    })
  </script>
@endpush
