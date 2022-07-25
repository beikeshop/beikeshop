@extends('admin::layouts.master')

@section('title', '插件列表')

@section('content')

  <div id="plugins-app" class="card" v-cloak>
    <div class="card-body">
      {{-- <button type="button" @click="upload" class="btn btn-primary">上传插件</button> --}}
      <el-upload
        class="upload-demo"
        action=""
        :limit="1"
        :show-file-list="false"
        :http-request="uploadFile"
        accept=".zip"
        >
        <el-button size="small" type="primary">上传插件 (仅支持 zip 文件)</el-button>
      </el-upload>
      <div class="mt-4" style="">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>插件类型</th>
              <th width="50%">插件描述</th>
              <th>状态</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="plugin, index in plugins" :key="index" v-if="plugins.length">
              <td>@{{ plugin.code }}</td>
              <td>@{{ plugin.type }}</td>
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
                <el-switch :disabled="!plugin.installed" v-model="plugin.status" @change="(e) => {pluginStatusChange(e, plugin.code)}"></el-switch>
              </td>
              <td>
                <div v-if="plugin.installed">
                  <a class="btn btn-outline-secondary btn-sm" :href="plugin.edit_url">编辑</a>
                  <a class="btn btn-outline-danger btn-sm" @click="installedPlugin(plugin.code, 'uninstall', index)">卸载</a>
                </div>
                <div v-else>
                  <a class="btn btn-outline-success btn-sm" @click="installedPlugin(plugin.code, 'install', index)">安装</a>
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
        pluginStatusChange(e, code) {
          const self = this;

          $http.put(`plugins/${code}/status`, {status: e * 1}).then((res) => {
            layer.msg(res.message)
          })
        },

        uploadFile(file) {
          $http.post('plugins/import', file).then((res) => {
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
