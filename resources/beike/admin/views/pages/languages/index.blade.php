@extends('admin::layouts.master')

@section('title', __('admin/common.language'))

@section('content')
  <div id="tax-classes-app" class="card" v-cloak>
    <div class="card-body h-min-600">
      @hook('admin.languages.index.before')
      <div class="mb-3 alert alert-info">{{ __('admin/language.help_install') }}</div>
      <table class="table">
        <thead>
          <tr>
            <th>{{ __('common.name') }}</th>
            <th>{{ __('currency.code') }}</th>
            <th>{{ __('common.sort_order') }}</th>
            @hook('admin.languages.index.thead')
            <th class="text-end">{{ __('common.action') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="language, index in languages" :key="index">
            <td>
              @{{ language.name }}
              <span class="badge bg-success" v-if="settingLocale == language.code">{{ __('common.default') }}</span>
            </td>
            <td>@{{ language.code }}</td>
            <td>@{{ language.sort_order }}</td>
            @hook('admin.languages.index.tbody')
            <td class="text-end">
              @hook('admin.languages.index.tbody.actions.before')
              <div v-if="language.id">
                <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">{{ __('common.edit') }}</button>
                <button :disabled="settingLocale == language.code" class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteItem(language.id, index)">{{ __('admin/common.uninstall') }}</button>
              </div>
              <div v-else>
                <button class="btn btn-outline-success btn-sm" @click="install(language.code, language.name, index)">{{ __('admin/common.install') }}</button>
              </div>
              @hook('admin.languages.index.tbody.actions.after')
            </td>
          </tr>
        </tbody>
      </table>

      @hook('admin.languages.index.after')
    </div>

    <el-dialog title="{{ __('admin/common.language') }}" :visible.sync="dialog.show" width="500px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        @hook('admin.languages.index.dialog.before')

        <el-form-item label="{{ __('common.name') }}">
          <el-input v-model="dialog.form.name" :disabled="true" placeholder="{{ __('common.name') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('currency.code') }}">
          <el-input v-model="dialog.form.code" :disabled="true" placeholder="{{ __('currency.code') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('common.sort_order') }}">
          <el-input v-model="dialog.form.sort_order" placeholder="{{ __('common.sort_order') }}"></el-input>
        </el-form-item>

        @hook('admin.languages.index.dialog.after')

        <el-form-item class="mt-5">
          @hook('admin.languages.index.dialog.submit.before')
          <el-button type="primary" @click="addFormSubmit('form')">{{ __('common.save') }}</el-button>
          <el-button @click="closeCustomersDialog('form')">{{ __('common.cancel') }}</el-button>
          @hook('admin.languages.index.dialog.submit.after')
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
@endsection

@push('footer')
  @include('admin::shared.vue-image')

  <script>
    var app = new Vue({
      el: '#tax-classes-app',

      data: {
        languages: @json($languages ?? []),
        settingLocale: @json(system_setting('base.locale') ?? 'zh_cn'),

        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            name: '',
            code: '',
            sort_order: '',
          },
        },

        rules: {
        },

        @hook('admin.languages.index.vue.data')
      },

      methods: {
        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index

          if (type == 'edit') {
            this.dialog.form = JSON.parse(JSON.stringify(this.languages[index]));
          }
        },

        addFormSubmit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'languages' : 'languages/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{ __('common.error_form') }}');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.type == 'add') {
                this.languages.push(res.data)
              } else {
                this.languages[this.dialog.index] = res.data
              }

              this.dialog.show = false
            })
          });
        },

        install(code, name, index) {
          $http.post('languages', {name, code}).then((res) => {
            this.languages[index] = res.data;
            this.$message.success(res.message);
            this.$forceUpdate();
          })
        },

        deleteItem(id, index) {
          $http.delete('languages/' + id).then((res) => {
            this.$message.success(res.message);
            this.languages[index].id = 0;
            this.$forceUpdate();
          })
        },

        closeCustomersDialog(form) {
          this.$refs[form].resetFields();
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.show = false
        },

        @hook('admin.languages.index.vue.methods')
      },

      @hook('admin.languages.index.vue.options')
    })
  </script>
@endpush
