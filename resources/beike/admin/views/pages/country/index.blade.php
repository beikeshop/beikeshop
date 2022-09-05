@extends('admin::layouts.master')

@section('title', __('admin/common.country'))

@section('content')
  <div id="tax-classes-app" class="card" v-cloak>
    <div class="card-body h-min-600">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCreate('add', null)">{{ __('common.add') }}</button>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>{{ __('common.name') }}</th>
            <th>{{ __('currency.code') }}</th>
            <th>{{ __('common.created_at') }}</th>
            <th>{{ __('common.updated_at') }}</th>
            <th>{{ __('common.sort_order') }}</th>
            <th>{{ __('common.status') }}</th>
            <th class="text-end">{{ __('common.action') }}</th>
          </tr>
        </thead>
        <tbody v-if="country.data.length">
          <tr v-for="country, index in country.data" :key="index">
            <td>@{{ country.id }}</td>
            <td>@{{ country.name }}</td>
            <td>@{{ country.code }}</td>
            <td>@{{ country.created_at }}</td>
            <td>@{{ country.updated_at }}</td>
            <td>@{{ country.sort_order }}</td>
            <td>
              <span v-if="country.status" class="text-success">{{ __('common.enable') }}</span>
              <span v-else class="text-secondary">{{ __('common.disable') }}</span>
            </td>
            <td class="text-end">
              <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">{{ __('common.edit') }}</button>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(country.id, index)">{{ __('common.delete') }}</button>
            </td>
          </tr>
        </tbody>
        <tbody v-else><tr><td colspan="8" class="border-0"><x-admin-no-data /></td></tr></tbody>
      </table>

      <el-pagination v-if="country.data.length" layout="prev, pager, next" background :page-size="country.per_page" :current-page.sync="page"
        :total="country.total"></el-pagination>
    </div>

    <el-dialog title="{{ __('admin/common.country') }}" :visible.sync="dialog.show" width="600px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="130px">
        <el-form-item label="{{ __('admin/country.country_name') }}" prop="name">
          <el-input v-model="dialog.form.name" placeholder="{{ __('admin/country.country_name') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('common.sort_order') }}">
          <el-input v-model="dialog.form.sort_order" placeholder="{{ __('common.sort_order') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('currency.code') }}">
          <el-input v-model="dialog.form.code" placeholder="{{ __('currency.code') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('common.status') }}">
          <el-switch v-model="dialog.form.status" :active-value="1" :inactive-value="0"></el-switch>
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
  @include('admin::shared.vue-image')

  <script>
    new Vue({
      el: '#tax-classes-app',

      data: {
        country: @json($country ?? []),
        page: 1,

        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            id: null,
            name: '',
            code: '',
            sort_order: '',
            status: 1,
          },
        },

        rules: {
          name: [{required: true,message: '{{ __('common.error_required', ['name' => __('admin/country.country_name')]) }}',trigger: 'blur'}, ],
        }
      },

      watch: {
        page: function() {
          this.loadData();
        },
      },

      methods: {
        loadData() {
          $http.get(`countries?page=${this.page}`).then((res) => {
            this.country = res.data.country;
          })
        },

        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index

          if (type == 'edit') {
            this.dialog.form = JSON.parse(JSON.stringify(this.country.data[index]));
          }
        },

        statusChange(e, index) {
          const id = this.country.data[index].id;

          // $http.put(`languages/${id}`).then((res) => {
          //   layer.msg(res.message);
          // })
        },

        addFormSubmit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'countries' : 'countries/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{ __('common.error_form') }}');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.type == 'add') {
                // this.country.data.push(res.data)
                this.loadData();
              } else {
                this.country.data[this.dialog.index] = res.data
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
            $http.delete('countries/' + id).then((res) => {
              this.$message.success(res.message);
              this.loadData();
              // self.country.data.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          this.$refs[form].resetFields();
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.show = false
        }
      }
    })
  </script>
@endpush
