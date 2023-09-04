@extends('admin::layouts.master')

@section('title', __('admin/tax_rate.tax_classes_index'))

@section('page-bottom-btns')
  <a href="{{ admin_route('settings.index') }}?tab=tab-checkout&line=tax_address" class="btn w-min-100 btn-outline-info" target="_blank">{{ __('admin/setting.tax_address') }}</a>
@endsection

@section('content')
  <ul class="nav-bordered nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link active" aria-current="page" href="{{ admin_route('tax_classes.index') }}">{{ __('admin/tax_rate.tax_classes_index') }}</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ admin_route('tax_rates.index') }}">{{ __('admin/tax_rate.index') }}</a>
    </li>
  </ul>

  <div id="tax-classes-app" class="card" v-cloak>
    <div class="card-body h-min-600">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCreate('add', null)">{{ __('common.add') }}</button>
      </div>
      <div class="table-push">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>{{ __('admin/region.name') }}</th>
              <th>{{ __('admin/region.describe') }}</th>
              <th>{{ __('common.created_at') }}</th>
              <th>{{ __('common.updated_at') }}</th>
              <th class="text-end">{{ __('common.action') }}</th>
            </tr>
          </thead>
          <tbody v-if="tax_classes.length">
            <tr v-for="tax, index in tax_classes" :key="index">
              <td>@{{ tax.id }}</td>
              <td>@{{ tax.title }}</td>
              <td :title="tax.description">@{{ stringLengthInte(tax.description) }}</td>
              <td>@{{ tax.created_at }}</td>
              <td>@{{ tax.updated_at }}</td>
              <td class="text-end">
                <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">{{ __('common.edit') }}</button>
                <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(tax.id, index)">{{ __('common.delete') }}</button>
              </td>
            </tr>
          </tbody>
          <tbody v-else><tr><td colspan="5" class="border-0"><x-admin-no-data /></td></tr></tbody>
        </table>
      </div>
    </div>

    <el-dialog title="{{ __('admin/tax_class.tax_classes_create') }}" :visible.sync="dialog.show" width="700px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        <el-form-item label="{{ __('admin/region.name') }}" prop="title">
          <el-input v-model="dialog.form.title" placeholder="{{ __('admin/region.name') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('admin/region.describe') }}" prop="description">
          <el-input v-model="dialog.form.description" placeholder="{{ __('admin/region.describe') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('admin/tax_class.rule') }}">
            <table class="table table-bordered" style="line-height: 1.6;">
              <thead>
                <tr>
                  <th>{{ __('admin/tax_rate.tax_rate') }}</th>
                  <th>{{ __('admin/tax_class.based_on') }}</th>
                  <th>{{ __('admin/tax_class.priority') }}</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="rule, index in dialog.form.tax_rules" :key="index">
                  <td>
                    <el-select v-model="rule.tax_rate_id" size="mini" placeholder="{{ __('common.please_choose') }}">
                      <el-option v-for="tax in source.all_tax_rates" :key="tax.id" :label="tax.name" :value="tax.id"></el-option>
                    </el-select>
                  </td>
                  <td>
                    <el-select v-model="rule.based" size="mini" placeholder="{{ __('common.please_choose') }}">
                      <el-option v-for="base in source.bases" :key="base" :label="base" :value="base"></el-option>
                    </el-select>
                  </td>
                  <td width="80px"><el-input v-model="rule.priority" size="mini" placeholder="{{ __('admin/tax_class.priority') }}"></el-input></td>
                  <td>
                    <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteRates(index)">{{ __('common.delete') }}</button>
                  </td>
                </tr>
              </tbody>
            </table>
            <el-button type="primary" icon="el-icon-plus" size="small" plain @click="addRates">{{ __('common.add') }}</el-button>
        </el-form-item>
        <el-form-item class="mt-5">
          <el-button type="primary" @click="addFormSubmit('form')">{{ __('common.save') }}</el-button>
          <el-button @click="closeCustomersDialog('form')">{{ __('common.cancel') }}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#tax-classes-app',

      data: {
        tax_classes: @json($tax_classes ?? []),

        source: {
          all_tax_rates: @json($all_tax_rates ?? []),
          bases: @json($bases ?? []),
        },

        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            id: null,
            title: '',
            description: '',
            tax_rules: [],
          },
        },

        rules: {
          title: [{required: true,message: "{{ __('common.error_required', ['name' => __('admin/region.name')])}}",trigger: 'blur'}, ],
          description: [{required: true,message: '{{ __('common.error_required', ['name' => __('admin/region.describe')])}}',trigger: 'blur'}, ],
        }
      },

      beforeMount() {
        // this.source.languages.forEach(e => {
        //   this.$set(this.dialog.form.name, e.code, '')
        //   this.$set(this.dialog.form.description, e.code, '')
        // })
      },

      methods: {
        descriptionFormat(text) {
          return bk.stringLengthInte(text)
        },

        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index

          if (type == 'edit') {
            let tax = this.tax_classes[index];

            this.dialog.form = {
              id: tax.id,
              title: tax.title,
              description: tax.description,
              tax_rules: tax.tax_rules,
            }
          }
        },

        addRates() {
          const tax_rate_id = this.source.all_tax_rates[0]?.id || 0;
          const based = this.source.bases[0] || '';

          this.dialog.form.tax_rules.push({tax_rate_id, based, priority: ''})
        },

        deleteRates(index) {
          this.dialog.form.tax_rules.splice(index, 1)
        },

        addFormSubmit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'tax_classes' : 'tax_classes/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{ __('common.error_form') }}');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.type == 'add') {
                this.tax_classes.push(res.data)
              } else {
                this.tax_classes[this.dialog.index] = res.data
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
            $http.delete('tax_classes/' + id).then((res) => {
              this.$message.success(res.message);
              self.tax_classes.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          this.$refs[form].resetFields();
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.form.tax_rules = []
          this.dialog.show = false
        }
      }
    })
  </script>
@endpush
