@extends('admin::layouts.master')

@section('title', __('admin/common.language'))

@section('content')
  <div id="tax-classes-app" class="card" v-cloak>
    <div class="card-body h-min-600">
      <div class="d-flex justify-content-between mb-4">
        {{-- <button type="button" class="btn btn-primary" @click="checkedCreate('add', null)">添加</button> --}}
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>{{ __('common.name') }}</th>
            <th>{{ __('currency.code') }}</th>
            <th>{{ __('currency.icon') }}</th>
            <th>{{ __('common.sort_order') }}</th>
            <th>{{ __('common.status') }}</th>
            <th class="text-end">{{ __('common.action') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="language, index in languages" :key="index">
            <td>@{{ language.id }}</td>
            <td>@{{ language.name }}</td>
            <td>@{{ language.code }}</td>
            <td>@{{ language.image }}</td>
            <td>@{{ language.sort_order }}</td>
            <td>
              <span v-if="language.status" class="text-success">{{ __('common.enable') }}</span>
              <span v-else class="text-secondary">{{ __('common.disable') }}</span>
            </td>
            <td class="text-end">
              <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">{{ __('common.edit') }}</button>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(language.id, index)">{{ __('common.delete') }}</button>
            </td>
          </tr>
        </tbody>
      </table>

      {{-- {{ $languages->links('admin::vendor/pagination/bootstrap-4') }} --}}
    </div>

    <el-dialog title="{{ __('admin/common.language') }}" :visible.sync="dialog.show" width="500px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        <el-form-item label="{{ __('common.name') }}" prop="name">
          <el-input v-model="dialog.form.name" placeholder="{{ __('common.name') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('currency.code') }}" prop="code">
          <el-input v-model="dialog.form.code" placeholder="{{ __('currency.code') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('currency.icon') }}" prop="image">
          <vue-image v-model="dialog.form.image"></vue-image>
        </el-form-item>

        <el-form-item label="{{ __('common.sort_order') }}">
          <el-input v-model="dialog.form.sort_order" placeholder="{{ __('common.sort_order') }}"></el-input>
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
        languages: @json($languages ?? []),

        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            id: null,
            name: '',
            code: '',
            image: '',
            sort_order: '',
            status: 1,
          },
        },

        rules: {
          name: [{required: true,message: '{{ __('common.error_required', ['name' => __('common.name')]) }}',trigger: 'blur'}, ],
          code: [{required: true,message: '{{ __('common.error_required', ['name' => __('currency.code')]) }}',trigger: 'blur'}, ],
          image: [{required: true,message: '{{ __('common.error_required', ['name' => __('currency.icon')]) }}',trigger: 'blur'}, ],
        }
      },

      methods: {
        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index

          if (type == 'edit') {
            let tax = this.languages[index];

            this.dialog.form = {
              id: tax.id,
              name: tax.name,
              code: tax.code,
              image: tax.image,
              sort_order: tax.sort_order,
              status: tax.status,
            }
          }
        },

        statusChange(e, index) {
          const id = this.languages[index].id;

          // $http.put(`languages/${id}`).then((res) => {
          //   layer.msg(res.message);
          // })
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

        deleteCustomer(id, index) {
          const self = this;
          this.$confirm('{{ __('common.confirm_delete') }}', '{{ __('common.text_hint') }}', {
            confirmButtonText: '{{ __('common.confirm') }}',
            cancelButtonText: '{{ __('common.cancel') }}',
            type: 'warning'
          }).then(() => {
            $http.delete('languages/' + id).then((res) => {
              this.$message.success(res.message);
              self.languages.splice(index, 1)
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
