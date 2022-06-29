@extends('admin::layouts.master')

@section('title', '顾客管理')

@section('content')
    <div id="customer-app-form" class="card">
        <div class="card-body">
            <el-form :model="form" :rules="rules" ref="form" label-width="100px" style="width: 460px;">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h6 class="mb-4">用户信息</h6>
                        <el-form-item label="用户名" prop="name">
                          <el-input v-model="form.name"></el-input>
                        </el-form-item>
                        <el-form-item label="邮箱" prop="email">
                          <el-input v-model="form.email"></el-input>
                        </el-form-item>
                        <el-form-item label="密码" prop="password">
                          <el-input v-model="form.password"></el-input>
                        </el-form-item>
                        <el-form-item label="用户组">
                          <el-select v-model="form.customer_group_id" placeholder="请选择">
                            <el-option
                              v-for="item in source.customer_group"
                              :key="item.value"
                              :label="item.name"
                              :value="item.value">
                            </el-option>
                          </el-select>
                        </el-form-item>
                        <el-form-item label="状态" prop="status">
                            <el-switch v-model="form.status"></el-switch>
                        </el-form-item>
                    </div>
                </div>
                <el-form-item>
                  <el-button type="primary" @click="submitForm('form')">提交</el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
    {{-- {{ admin_route($customer->id ? 'customers.update' : 'customers.store', $customer) }} --}}
@endsection

@push('footer')
<script>
    new Vue({
      el: '#customer-app-form',
      data: {
        form: {
            id: '',
            name: '',
            email: '',
            password: '',
            customer_group_id: 1,
            status: false,
            address: [
                {
                    name: '',
                    phone: '',
                    country_id: '',
                    city_id: '',
                    address_1: '',
                    address_2: '',
                }
            ]
        },
        source: {
            customer_group: [
                {name: '超级vip', value: 1},
                {name: '普通vip', value: 2},
                {name: '青铜', value: 3},
            ],
        },
        rules: {
            name: [
              { required: true, message: '请输入用户名', trigger: 'blur' },
            ],
        }
      },

      methods: {
        submitForm(form) {
            this.$refs[form].validate((valid) => {
              if (!valid) {
                this.$message.error('请检查表单是否填写正确');
              }

            });
        }
      }
    });
</script>
@endpush
