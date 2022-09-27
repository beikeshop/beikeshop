@extends('admin::layouts.master')

@section('title', __('admin/plugin.plugin_list'))

@section('content')

  <div id="plugins-app" class="card" v-cloak>
    <div class="card-body h-min-600">
      <div class="d-flex align-items-center justify-content-between">
        <el-upload
          class="upload-demo"
          action=""
          :limit="1"
          :show-file-list="false"
          :http-request="uploadFile"
          accept=".zip"
          >
          <el-button size="small" type="primary">{{ __('admin/plugin.plugin_upload') }}</el-button>
        </el-upload>
        <a href="{{ admin_route('marketing.index') }}" class="btn btn-outline-info">{{ __('admin/marketing.marketing_list') }}</a>
      </div>
      <div class="mt-4" style="">
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
                  <div class="me-2" style="width: 50px;"><img :src="plugin.icon" class="img-fluid"></div>
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
                  <a class="btn btn-outline-secondary btn-sm" :href="plugin.edit_url">{{ __('admin/common.edit') }}</a>
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

        uploadFile(file) {
          let formData = new FormData();
          formData.append("file", file.file, file.file.name);

          $http.post('plugins/import', formData).then((res) => {
            layer.msg(res.message)
            location.reload();
          })
        },

        installedPlugin(code, type, index) {
          const self = this;

          $http.post(`plugins/${code}/${type}`).then((res) => {
            layer.msg(res.message)
            self.plugins[index].installed = type == 'install' ? true : false;
          })
        }
      }
    })
  </script>
@endpush
