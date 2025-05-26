@extends('admin::layouts.master')

@section('title', __('admin/common.currency'))

@section('content')
  <div id="tax-classes-app" class="card" v-cloak>
    <div class="card-body h-min-600">
      @hook('admin.currency.index.content.before')
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCreate('add', null)">{{ __('common.add') }}</button>
        <a href="{{ admin_route('settings.index') }}?tab=tab-checkout&line=rate_api_key" class="btn w-min-100 btn-outline-info" target="_blank">{{ __('admin/setting.rate_api_key') }}</a>
      </div>
      <div class="table-push">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>{{ __('common.name') }}</th>
              <th>{{ __('currency.code') }}</th>
              <th>{{ __('currency.symbol_left') }}</th>
              <th>{{ __('currency.symbol_right') }}</th>
              <th>{{ __('currency.decimal_place') }}</th>
              <th>{{ __('currency.value') }}</th>
              <th>{{ __('currency.latest_value') }}</th>
              <th>{{ __('common.status') }}</th>
              @hook('admin.currency.index.table.headers')
              <th class="text-end">{{ __('common.action') }}</th>
            </tr>
          </thead>
          <tbody v-if="currencies.length">
            <tr v-for="item, index in currencies" :key="index">
              <td>@{{ item.id }}</td>
              <td>
                <div>@{{ item.name }}</div>
                <span v-if="item.code == defaultCurrency" class="badge text-bg-success text-white">{{ __('admin/setting.default_currency') }}</span>
              </td>
              <td>@{{ item.code }}</td>
              <td>@{{ item.symbol_left }}</td>
              <td>@{{ item.symbol_right }}</td>
              <td>@{{ item.decimal_place }}</td>
              <td>@{{ item.value }}</td>
              <td>@{{ item.latest_value }}</td>
              <td>
                <span v-if="item.status" class="text-success">{{ __('common.enable') }}</span>
                <span v-else class="text-secondary">{{ __('common.disable') }}</span>
              </td>
              @hook('admin.currency.index.table.body')
              <td class="text-end">
                @hook('admin.currency.index.table.body.actions.before')
                <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">{{ __('common.edit') }}</button>
                <button class="btn btn-outline-danger btn-sm ml-1" :disabled="item.code == defaultCurrency" type="button" @click="deleteCustomer(item.id, index)">{{ __('common.delete') }}</button>
                @hook('admin.currency.index.table.body.actions.after')
              </td>
            </tr>
          </tbody>
          <tbody v-else><tr><td colspan="9" class="border-0"><x-admin-no-data /></td></tr></tbody>
        </table>
      </div>

    </div>

    <el-dialog title="{{ __('admin/common.currency') }}" :visible.sync="dialog.show" width="670px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="130px">
        @hook('admin.currency.index.dialog.form.before')

        <el-form-item label="{{ __('common.name') }}" prop="name">
          <el-input v-model="dialog.form.name" placeholder="{{ __('common.name') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('currency.code') }}" prop="code">
          <el-input v-model="dialog.form.code" :disabled="dialog.form.id != ''" placeholder="{{ __('currency.code') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('currency.symbol_left') }}">
          <el-input v-model="dialog.form.symbol_left" placeholder="{{ __('currency.symbol_left') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('currency.symbol_right') }}">
          <el-input v-model="dialog.form.symbol_right" placeholder="{{ __('currency.symbol_right') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('currency.decimal_place') }}" prop="decimal_place">
          <el-input v-model="dialog.form.decimal_place" placeholder="{{ __('currency.decimal_place') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('currency.value') }}" prop="value">
          <el-input v-model="dialog.form.value" placeholder="{{ __('currency.value') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('admin/setting.default_currency') }}">
          <el-switch v-model="dialog.form.default_currency" :active-value="1" :inactive-value="0" :disabled="defaultCurrencyDisabled"></el-switch>
        </el-form-item>

        <el-form-item label="{{ __('common.status') }}">
          <el-switch v-model="dialog.form.status" :active-value="1" :inactive-value="0" :disabled="defaultCurrencyDisabled"></el-switch>
        </el-form-item>

        @hook('admin.currency.index.dialog.form.after')

        <el-form-item class="mt-5">
          @hook('admin.currency.index.dialog.submit.before')
          <el-button type="primary" @click="addFormSubmit('form')">{{ __('common.save') }}</el-button>
          <el-button @click="dialog.show = false">{{ __('common.cancel') }}</el-button>
          @hook('admin.currency.index.dialog.submit.after')
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
@endsection

@push('footer')
  @include('admin::shared.vue-image')

  <script>
    @hook('admin.currency.index.script.before')

    var app = new Vue({
      el: '#tax-classes-app',

      data: {
        currencies: @json($currencies ?? []),

        defaultCurrency: @json(system_setting('base.currency')),

        dialog: {
          show: false,
          index: '',
          type: 'add',
          form: {
            id: '',
            name: '',
            code: '',
            symbol_left: '',
            symbol_right: '',
            decimal_place: '',
            default_currency: false,
            value: '',
            status: 1,
          },
        },

        rules: {
          name: [{required: true,message: '{{ __('common.error_required', ['name' => __('common.name')]) }}', trigger: 'blur'}, ],
          code: [{required: true,message: '{{ __('common.error_required', ['name' => __('currency.code')]) }}', trigger: 'blur'}, ],
          value: [{required: true,message: '{{ __('common.error_required', ['name' => __('currency.value')]) }}',trigger: 'blur'}, ],
          decimal_place: [{required: true,message: '{{ __('common.error_required', ['name' => __('currency.decimal_place')]) }}',trigger: 'blur'}, ],
        },

        @hook('admin.currency.index.vue.data')
      },

      computed: {
        defaultCurrencyDisabled() {
          if (this.dialog.index == '') {
            return false
          }
          return this.dialog.form.code == this.defaultCurrency
        }
      },

      methods: {
        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type

          if (type == 'edit') {
            this.dialog.index = index
            let form = JSON.parse(JSON.stringify(this.currencies[index]));
            form.default_currency = Number(this.currencies[index].code == this.defaultCurrency)
            this.dialog.form = form
          }
        },

        addFormSubmit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'currencies' : 'currencies/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{ __('common.error_form') }}');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.type == 'add') {
                this.currencies.push(res.data)
              } else {
                this.currencies[this.dialog.index] = res.data
              }

              if (self.dialog.form.default_currency) {
                self.defaultCurrency = self.dialog.form.code
              }

              this.dialog.show = false
            })
          });
        },

        deleteCustomer(id, index) {
          const self = this;
          this.$confirm('{{ __('common.confirm_delete') }}', '{{ __('common.text_hint') }}', {
            confirmButtonText: '{{ __('common.confirm') }}',
            cancelButtonText: '{{ __('common.cancel') }}',
            type: 'warning'
          }).then(() => {
            $http.delete('currencies/' + id).then((res) => {
              if (res.status == 'fail') {
                layer.msg(res.message, () => {});
                return;
              }

              layer.msg(res.message);
              self.currencies.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          this.$refs[form].resetFields();
          this.dialog.index = '';
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
        },

        @hook('admin.currency.index.vue.methods')
      },

      @hook('admin.currency.index.vue.options')
    })

    @hook('admin.currency.index.script.after')
  </script>
@endpush
