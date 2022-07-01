@extends('admin::layouts.master')

@section('title', '插件列表')

@section('content')

  <div id="plugins-app" class="card" v-cloak>
    <div class="card-body">
      <a href="{{ admin_route('categories.create') }}" class="btn btn-primary">创建插件</a>
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

          $.ajax({
            url: `/admin/plugins/${code}/status`,
            type: 'PUT',
            data: {status: e * 1},
            success: function(res) {
              layer.msg(res.message)
            },
          })
        },

        installedPlugin(code, type, index) {
          const self = this;

          $.ajax({
            url: `/admin/plugins/${code}/${type}`,
            type: 'post',
            success: function(res) {
              layer.msg(res.message)
              self.plugins[index].installed = true;
            },
          })
        }
      }
    })
  </script>
@endpush
