@extends('admin::layouts.master')

@section('title', __('admin/common.admin_user'))

@section('content')
  <ul class="nav-bordered nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link active" aria-current="page" href="{{ admin_route('admin_users.index') }}">{{ __('admin/common.admin_user') }}</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ admin_route('admin_roles.index') }}">{{ __('admin/common.admin_role') }}</a>
    </li>
  </ul>

  <div id="tax-classes-app" class="card" v-cloak>
    <div class="card-body h-min-600">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCreate('add', null)">{{ __('admin/user.admin_users_create') }}</button>
      </div>
      <div class="table-push">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>{{ __('common.name') }}</th>
              <th>{{ __('common.email') }}</th>
              <th>{{ __('admin/common.admin_role') }}</th>
              <th>{{ __('common.created_at') }}</th>
              <th>{{ __('common.updated_at') }}</th>
              <th class="text-end">{{ __('common.action') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="tax, index in admin_users" :key="index">
              <td>@{{ tax.id }}</td>
              <td>@{{ tax.name }}</td>
              <td>@{{ tax.email }}</td>
              <td>
                <span v-for="role, role_index in tax.roles_name" :key="role_index">
                  @{{ role }}
                </span>
              </td>
              <td>@{{ tax.created_at }}</td>
              <td>@{{ tax.updated_at }}</td>
              <td class="text-end">
                <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">{{ __('common.edit') }}</button>
                <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(tax.id, index)">{{ __('common.delete') }}</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      {{-- {{ $admin_users->links('admin::vendor/pagination/bootstrap-4') }} --}}
    </div>

    <el-dialog title="{{ __('admin/common.admin_user') }}" :visible.sync="dialog.show" width="600px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        <el-form-item label="{{ __('common.name') }}" prop="name">
          <el-input v-model="dialog.form.name" placeholder="{{ __('common.name') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('common.email') }}" prop="email">
          <el-input v-model="dialog.form.email" placeholder="{{ __('common.email') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('shop/login.password') }}" :prop="dialog.form.id === null || dialog.form.id == '' ? 'password' : ''">
          <el-input v-model="dialog.form.password" placeholder="{{ __('shop/login.password') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('common.language') }}">
          <el-select v-model="dialog.form.locale" placeholder="">
            <el-option
              v-for="language in source.languages"
              :key="language.code"
              :label="language.name"
              :value="language.code">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="{{ __('admin/admin_roles.role') }}" prop="roles">
          <el-checkbox-group v-model="dialog.form.roles">
            <el-checkbox v-for="roles, index in source.roles" :label="roles.id">@{{roles.name}}</el-checkbox>
          </el-checkbox-group>
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
        admin_users: @json($admin_users ?? []),

        source: {
          all_tax_rates: @json($all_tax_rates ?? []),
          roles: @json($admin_roles ?? []),
          languages: @json(locales() ?? []),
          {{-- language: @json($admin_language ?? 'en'), --}}
        },

        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            id: null,
            name: '',
            email: '',
            locale: @json($admin_language['code'] ?? 'en'),
            password: '',
            roles: [],
          },
        },

        rules: {
          name: [{required: true,message: '{{ __('common.error_required', ['name' => __('common.name')]) }}', trigger: 'blur'}, ],
          email: [{required: true,message: '{{ __('common.error_required', ['name' => __('common.email')]) }}', trigger: 'blur'}, ],
          password: [{required: true,message: '{{ __('common.error_required', ['name' => __('shop/login.password')]) }}', trigger: 'blur'}, ],
          roles: [{type: 'array', required: true, message: '{{ __('admin/admin_roles.error_roles') }}', trigger: 'blur'}],
        }
      },

      methods: {
        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index

          if (type == 'edit') {
            let tax = this.admin_users[index];

            this.dialog.form = {
              id: tax.id,
              name: tax.name,
              email: tax.email,
              locale: tax.locale,
              roles: tax.roles.map(e => e.id),
            }
          }
        },

        addFormSubmit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'admin_users' : 'admin_users/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{ __('common.error_form') }}');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.type == 'add') {
                this.admin_users.push(res.data)
              } else {
                this.admin_users[this.dialog.index] = res.data
              }
              window.location.reload();
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
            $http.delete('admin_users/' + id).then((res) => {
              this.$message.success(res.message);
              self.admin_users.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          this.$refs[form].resetFields();
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.form.roles = [];
          this.dialog.form.locale =  @json($admin_language['code'] ?? 'en');
          this.dialog.show = false
        }
      }
    })
  </script>
@endpush
