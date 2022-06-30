@extends('admin::layouts.master')

@section('title', '顾客管理')

@section('content')
  <div id="customer-app-form" class="card">
    <div class="card-body">
      <el-form :model="form" :rules="rules" ref="form" label-width="100px">
        <el-tabs v-model="customerTab">
          <el-tab-pane label="用户信息" name="customer">
            <div class="form-max-w">
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
                  <el-option v-for="item in source.customer_group" :key="item.id" :label="item.description.name"
                    :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="状态" prop="status">
                <el-switch v-model="form.status"></el-switch>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" @click="submitForm('form')">提交</el-button>
              </el-form-item>
            </div>
          </el-tab-pane>
          <el-tab-pane label="地址管理" name="address">
            <button class="btn btn-primary mb-3" type="button" @click="editAddress">添加地址</button>
            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>名称</th>
                  <th>电话</th>
                  <th>注册来源</th>
                  <th>状态</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody v-if="form.address.length">
                <tr v-for="address, index in form.address" :key="index">
                  <td>@{{ index }}</td>
                  <td>@{{ address.name }}</td>
                  <td>@{{ address.phone }}</td>
                  <td>222</td>
                  <td>222</td>
                  <td>
                    <button class="btn btn-outline-secondary btn-sm" type="button" @click="editAddress(index)">编辑</button>
                  </td>{{--
                </tr> --}}
              </tbody>
              <tbody v-else>
                <tr>
                  <td colspan="6" class="text-center">
                    <span class="me-2">当前账号无地址</span> <el-link type="primary" @click="editAddress">新增地址</el-link>
                  </td>{{--
                </tr> --}}
              </tbody>
            </table>
          </el-tab-pane>
        </el-tabs>
      </el-form>
    </div>

    <el-dialog title="编辑地址" :visible.sync="dialogAddress.show" width="580px">
      <el-form ref="addressForm" :rules="addressRules" :model="dialogAddress.form" label-width="100px">
        <el-form-item label="姓名" prop="name">
          <el-input v-model="dialogAddress.form.name"></el-input>
        </el-form-item>
        <el-form-item label="联系电话" prop="phone">
          <el-input maxlength="11" v-model="dialogAddress.form.phone"></el-input>
        </el-form-item>
        <el-form-item label="地址">
          <el-row>
            <el-col :span="8">
              <el-select v-model="dialogAddress.form.country_id" placeholder="请选择">
                <el-option v-for="item in source.countrys" :key="item.country_id" :label="item.name"
                  :value="item.country_id">
                </el-option>
              </el-select>
            </el-col>
          </el-row>
        </el-form-item>
        <el-form-item label="详细地址 1" prop="address_1">
          <el-input v-model="dialogAddress.form.address_1"></el-input>
        </el-form-item>
        <el-form-item label="详细地址 2">
          <el-input v-model="dialogAddress.form.address_2"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="addressFormSubmit('addressForm')">保存</el-button>
          <el-button @click="closeAddressDialog('addressForm')">取消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
  {{-- {{ admin_route($customer->id ? 'customers.update' : 'customers.store', $customer) }} --}}
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#customer-app-form',
      data: {
        customerTab: 'customer',
        form: {
          id: '',
          name: '',
          email: '',
          password: '',
          customer_group_id: 1,
          status: false,
          address: []
        },
        source: {
          customer_group: @json($customer_groups),
          countrys: [
            {country_id: 44, name: "中国"},
            {country_id: 22, name: "美国"},
            {country_id: 122, name: "俄罗斯"},
            {country_id: 123, name: "英国"},
            {country_id: 113, name: "法国"},
          ]
        },
        dialogAddress: {
          show: false,
          index: null,
          form: {
            name: '',
            phone: '',
            country_id: 44,
            city_id: '',
            address_1: '',
            address_2: '',
          }
        },
        rules: {
          name: [{required: true, message: '请输入用户名', trigger: 'blur'}, ],
        },
        addressRules: {
          name: [{required: true, message: '请输入姓名', trigger: 'blur'}, ],
          phone: [{required: true, message: '请输入联系电话', trigger: 'blur'}, ],
          address_1: [{required: true, message: '请输入详细地址 1', trigger: 'blur'}, ],
        }
      },

      methods: {
        submitForm(form) {
          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }
          });
        },

        editAddress(index) {
          if (typeof index == 'number') {
            this.dialogAddress.index = index;

            this.$nextTick(() => {
              this.dialogAddress.form = JSON.parse(JSON.stringify(this.form.address[index]))
            })
          }

          this.dialogAddress.show = true
        },

        addressFormSubmit(form) {
          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            if (this.dialogAddress.index === null) {
              this.form.address.push(JSON.parse(JSON.stringify(this.dialogAddress.form)));
            } else {
              this.form.address[this.dialogAddress.index] = JSON.parse(JSON.stringify(this.dialogAddress.form));
            }

            this.$refs[form].resetFields();
            this.dialogAddress.show = false
            this.dialogAddress.index = null;
          });
        },

        closeAddressDialog(form) {
          this.$refs[form].resetFields();
          this.dialogAddress.show = false
          this.dialogAddress.index = null;
        },
      }
    });
  </script>
@endpush
