@extends('admin::layouts.master')

@section('title', '后台用户')

@section('content')
  <ul class="nav-bordered nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link active" aria-current="page" href="{{ admin_route('admin_users.index') }}">后台用户</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ admin_route('admin_roles.index') }}">用户角色</a>
    </li>
  </ul>

  <div id="tax-classes-app" class="card" v-cloak>
    <div class="card-body h-min-600">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCreate('add', null)">创建用户</button>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>账号名称</th>
            <th>邮箱</th>
            <th>角色</th>
            <th>创建时间</th>
            <th>修改时间</th>
            <th class="text-end">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="tax, index in admin_users" :key="index">
            <td>@{{ tax.id }}</td>
            <td>@{{ tax.name }}</td>
            <td>@{{ tax.email }}</td>
            <td>
              <span v-for="role, role_index in tax.roles" :key="role_index">
                @{{ role.name }} @{{ (tax.roles.length - 1 != role_index) ? '、' : '' }}
              </span>
            </td>
            <td>@{{ tax.created_at }}</td>
            <td>@{{ tax.updated_at }}</td>
            <td class="text-end">
              <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">编辑</button>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(tax.id, index)">删除</button>
            </td>
          </tr>
        </tbody>
      </table>

      {{-- {{ $admin_users->links('admin::vendor/pagination/bootstrap-4') }} --}}
    </div>

    <el-dialog title="用户" :visible.sync="dialog.show" width="600px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        <el-form-item label="账号名称" prop="name">
          <el-input v-model="dialog.form.name" placeholder="账号名称"></el-input>
        </el-form-item>

        <el-form-item label="邮箱" prop="email">
          <el-input v-model="dialog.form.email" placeholder="邮箱"></el-input>
        </el-form-item>

        <el-form-item label="密码" :prop="dialog.form.id === null || dialog.form.id == '' ? 'password' : ''">
          <el-input v-model="dialog.form.password" placeholder="密码"></el-input>
        </el-form-item>

        <el-form-item label="角色" prop="roles">
          <el-checkbox-group v-model="dialog.form.roles">
            <el-checkbox v-for="roles, index in source.roles" :label="roles.id">@{{roles.name}}</el-checkbox>
          </el-checkbox-group>
        </el-form-item>

        <el-form-item class="mt-5">
          <el-button type="primary" @click="addFormSubmit('form')">保存</el-button>
          <el-button @click="closeCustomersDialog('form')">取消</el-button>
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
          roles: @json($admin_roles ?? [])
        },

        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            id: null,
            name: '',
            email: '',
            password: '',
            roles: [],
          },
        },

        rules: {
          name: [{required: true,message: '请输入账号名称', trigger: 'blur'}, ],
          email: [{required: true,message: '请输入邮箱', trigger: 'blur'}, ],
          password: [{required: true,message: '请输入密码', trigger: 'blur'}, ],
          roles: [{type: 'array', required: true, message: '请至少选择一个角色', trigger: 'blur'}],
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
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.type == 'add') {
                this.admin_users.push(res.data)
              } else {
                this.admin_users[this.dialog.index] = res.data
              }

              this.dialog.show = false
            })
          });
        },

        deleteCustomer(id, index) {
          const self = this;
          this.$confirm('确定要删除用户吗？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            $http.delete('admin_users/' + id).then((res) => {
              this.$message.success(res.message);
              self.admin_users.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.form.roles = [];
          this.dialog.show = false
          this.$refs[form].resetFields();
        }
      }
    })
  </script>
@endpush
