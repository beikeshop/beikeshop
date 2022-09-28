@extends('admin::layouts.master')

@section('title', __('admin/plugin.plugins_show'))

@section('page-title-right')
  <button type="button" class="btn btn-primary save-btn" onclick="app.submit('form')">{{ __('common.save') }}</button>
@endsection

@section('content')
  <div class="card mb-4" id="app">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h6 class="mb-0">{{ $plugin->name }}</h6>
        <button type="button" @click="addRow()" class="btn btn-sm btn-outline-primary">{{ __('common.add') }}</button>
      </div>

      @if (session('success'))
        <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
      @endif

      <el-form ref="form" :model="form" class="form-wrap" :inline-message="true">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th style="width: 150px">{{ __('Social::setting.entry_provider') }}</th>
              <th>{{ __('Social::setting.entry_status') }}</th>
              <th>{{ __('Social::setting.entry_key') }}</th>
              <th>{{ __('Social::setting.entry_secret') }}</th>
              <th>{{ __('Social::setting.entry_callback') }}</th>
              <th style="width: 100px">{{ __('Social::setting.entry_sort_order') }}</th>
              <th class="text-end"></th>
            </tr>
          </thead>
          <tbody v-if="form.social.length">
            <tr v-for="(item, index) in form.social" :key="index">
              <td>
                <el-form-item prop="provider" class="mb-0">
                  <el-select size="small" v-model="item.provider" @change="(e) => {providerChange(e, index)}" placeholder="{{ __('Social::setting.provider') }}">
                    <el-option
                      v-for="item in source.providers"
                      :key="item.code"
                      :label="item.label"
                      :value="item.code">
                    </el-option>
                  </el-select>
                </el-form-item>
              </td>
              <td>
                <el-form-item label="" prop="entry_status" class="mb-0">
                  <el-switch v-model="item.status" :active-value="1" :inactive-value="0"></el-switch>
                </el-form-item>
              </td>
              <td>
                <el-form-item
                  label="" :prop="`social[${index}].key`" class="mb-0"
                  :rules="[
                    { required: true, message: '{{ __('common.error_required', ['name' => __('Social::setting.entry_key')]) }}', trigger: ['blur', 'change'] },]"
                  >
                  <el-input size="small" v-model="item.key" placeholder="{{ __('Social::setting.entry_key') }}"></el-input>
                </el-form-item>
              </td>
              <td>
                <el-form-item
                  label="" :prop="`social[${index}].secret`" class="mb-0"
                  :rules="[
                  { required: true, message: '{{ __('common.error_required', ['name' => __('Social::setting.entry_secret')]) }}', trigger: ['blur', 'change'] },]"
                >
                  <el-input size="small" v-model="item.secret" placeholder="{{ __('Social::setting.entry_secret') }}"></el-input>
                </el-form-item>
              </td>
              <td>
                <el-form-item label="" class="mb-0">
                  <div class="input-group">
                    <input size="small" class="form-control" :value="callbackUrl(item.callback)" disabled placeholder="{{ __('Social::setting.entry_callback') }}"></input>
                    <a href="javascript:void(0)" class="btn btn-outline-secondary opacity-75 copy-code" :data-clipboard-text="item.callback"><i class="bi bi-front"></i></a>
                  </div>
                </el-form-item>
              </td>
              <td>
                <el-form-item label="" prop="sort_order" class="mb-0">
                  <el-input size="small" v-model="item.sort_order" placeholder="{{ __('Social::setting.entry_sort_order') }}"></el-input>
                </el-form-item>
              </td>
              <td class="text-end">
                <button type="button" @click="form.social.splice(index, 1)" class="btn btn-outline-danger btn-sm ml-1"><i class="bi bi-x-lg"></i></button>
              </td>
            </tr>
          </tbody>
          <tbody v-else><td colspan="7"><x-admin-no-data /></td></tbody>
        </table>
      </el-form>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <h6 class="border-bottom pb-3 mb-4">{{ __('Social::setting.text_help_msg') }}</h6>
      <ol class="list-group list-group-numbered lh-lg text-secondary">
        <li>{{ __('Social::setting.text_omni_explain') }}</li>
        <li>{{ __('Social::setting.text_omni_explain_2') }}</li>
        <li>{{ __('Social::setting.text_wechat_title') }}
            <a target="_blank" href="https://open.weixin.qq.com/">{{ __('Social::setting.text_wechat_info') }}</a>
        </li>
        <li>{{ __('Social::setting.text_qq_title') }}
            <a target="_blank" href="https://connect.qq.com/">{{ __('Social::setting.text_qq_info') }}</a>
        </li>
        <li>{{ __('Social::setting.text_weibo_title') }}
            <a target="_blank" href="http://open.weibo.com/">{{ __('Social::setting.text_weibo_info') }}</a>
        </li>
        <li>{{ __('Social::setting.text_facebook_title') }}
          <a target="_blank" href="https://developers.facebook.com/">Facebook</a>
        </li>
        <li>{{ __('Social::setting.text_google_title') }}
          <a target="_blank" href="https://console.developers.google.com/projectcreate/">Google</a>
        </li>
        <li>{{ __('Social::setting.text_Twitter_title') }}
          <a target="_blank" href="https://apps.twitter.com/">Twitter</a>
        </li>
        .......
      </ol>
    </div>
  </div>

  <style>
    .el-form-item__error--inline {
      margin-left: 0;
    }

    .el-form-item__content {
      line-height: 1;
    }
  </style>

<script src="{{ asset('vendor/clipboard/clipboard.min.js') }}"></script>
<script>
  new ClipboardJS('.copy-code')
  let app = new Vue({
    el: '#app',

    data: {
      form: {
        social: @json($plugin->getSetting('setting') ?? []),
      },

      source: {
        providers: @json(Plugin\Social\Repositories\CustomerRepo::allProviders())
      },

      rules: {

      }
    },

    methods: {
      submit(form) {
        this.$refs[form].validate((valid) => {
          if (!valid) {
            this.$message.error('{{ __('common.error_form') }}');
            return;
          }

          $http.post("{{ admin_route('plugin.social.setting') }}", this.form.social).then((res) => {
            layer.msg(res.message)
          })
        });
      },

      callbackUrl(code) {
        return `{{ shop_route('home.index') }}/${code}`;
      },

      providerChange(e, index) {
        this.form.social[index].callback = 'plugin/social/callbacks/' + e
      },

      addRow() {
        this.form.social.push({provider: this.source.providers[1].code, status: 1, key: '', secret: '', callback: `plugin/social/callbacks/${this.source.providers[1].code}`, sort_order: this.form.social.length})
      }
    }
  })
</script>
@endsection
