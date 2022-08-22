@extends('admin::layouts.master')

@section('title', '客户管理')

@section('content')
  <div id="customer-app" class="card" v-cloak>
    <div class="card-body">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCustomersCreate">创建客户</button>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>邮箱</th>
            <th>名称</th>
            <th>注册来源</th>
            <th>客户组</th>
            <th>状态</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="customer, index in customers.data" :key="index">
            <td>@{{ customer.id }}</td>
            <td>@{{ customer.email }}</td>
            <td>
              <div class="d-flex align-items-center">
                {{-- <img src="@{{ customer.avatar }}" class="img-fluid rounded-circle me-2" style="width: 40px;"> --}}
                <div>@{{ customer.name }}</div>
              </div>
            </td>
            <td>@{{ customer.from }}</td>
            <td>@{{ customer.customer_group_name }}</td>
            <td>
              <span v-if="customer.status" class="text-success">{{ __('common.enable') }}</span>
              <span v-else class="text-secondary">{{ __('common.disable') }}</span>
            </td>
            <td>
              <a class="btn btn-outline-secondary btn-sm" :href="customer.edit">编辑</a>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(customer.delete, index)">删除</button>
            </td>
          </tr>
        </tbody>
      </table>

      <el-pagination layout="prev, pager, next" background :page-size="customers.per_page" :current-page.sync="page"
        :total="customers.total"></el-pagination>
    </div>

    <el-dialog title="创建客户" :visible.sync="dialogCustomers.show" width="600px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">
      <el-form ref="form" :rules="rules" :model="dialogCustomers.form" label-width="100px">
        <el-form-item label="用户名" prop="name">
          <el-input v-model="dialogCustomers.form.name" placeholder="用户名"></el-input>
        </el-form-item>
        <el-form-item label="邮箱" prop="email">
          <el-input v-model="dialogCustomers.form.email" placeholder="邮箱"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input v-model="dialogCustomers.form.password" placeholder="密码"></el-input>
        </el-form-item>
        <el-form-item label="用户组">
          <el-select v-model="dialogCustomers.form.customer_group_id" placeholder="请选择">
            <el-option v-for="item in source.customer_group" :key="item.id" :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="状态" prop="status">
          <el-switch v-model="dialogCustomers.form.status" :active-value="1" :inactive-value="0"></el-switch>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="addCustomersFormSubmit('form')">保存</el-button>
          <el-button @click="closeCustomersDialog('form')">取消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#customer-app',

      data: {
        page: 1,
        customers: @json($customers ?? []),

        source: {
          customer_group: @json($customer_groups ?? []),
        },

        dialogCustomers: {
          show: false,
          form: {
            id: null,
            name: '',
            email: '',
            password: '',
            customer_group_id: @json($customer_groups[0]['id'] ?? ''),
            status: 1,
          },
        },

        rules: {
          name: [{required: true,message: '请输入用户名',trigger: 'blur'}, ],
          email: [
            {required: true, message: '请输入邮箱', trigger: 'blur'},
            {type: 'email', message: '请输入正确邮箱格式' ,trigger: 'blur'},
          ],
          password: [{required: true,message: '请输入密码',trigger: 'blur'}, ],
        }
      },

      watch: {
        page: function() {
          this.loadData();
        },
      },

      // mounted: function() {
      // },

      methods: {
        loadData() {
          $http.get(`customers?page=${this.page}`).then((res) => {
            this.customers = res.data.customers;
          })
        },

        checkedCustomersCreate() {
          this.dialogCustomers.show = true
        },

        addCustomersFormSubmit(form) {
          const self = this;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            $http.post('customers', this.dialogCustomers.form).then((res) => {
              this.$message.success(res.message);
              this.loadData();// this.customers.data.push(res.data);
              this.dialogCustomers.show = false
            })
          });
        },

        deleteCustomer(url, index) {
          const self = this;
          this.$confirm('确定要删除用户吗？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            $http.delete(url).then((res) => {
              self.$message.success(res.message);
              self.customers.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          this.$refs[form].resetFields();
          this.dialogCustomers.show = false
        }
      }
    })
  </script>
@endpush
