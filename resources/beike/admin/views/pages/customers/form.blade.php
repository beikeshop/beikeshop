@extends('admin::layouts.master')

@section('title', '顾客管理')

@section('content')
  <div id="customer-app-form" class="card" v-cloak>
    <div class="card-body">
      <el-form :model="form" :rules="rules" ref="form" label-width="100px">
        <el-tabs v-model="customerTab">
          <el-tab-pane label="用户信息" name="customer">
            <div class="form-max-w">
              <el-form-item label="用户名" prop="name">
                <el-input v-model="form.name" placeholder="用户名"></el-input>
              </el-form-item>
              <el-form-item label="邮箱" prop="email">
                <el-input v-model="form.email" placeholder="邮箱"></el-input>
              </el-form-item>
              <el-form-item label="密码" prop="password">
                <el-input v-model="form.password" placeholder="留空则保持原密码不变"></el-input>
              </el-form-item>
              <el-form-item label="用户组">
                <el-select v-model="form.customer_group_id" placeholder="请选择">
                  <el-option v-for="item in source.customer_group" :key="item.id" :label="item.description.name"
                    :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="状态" prop="status">
                <el-switch v-model="form.status" :active-value="1" :inactive-value="0"></el-switch>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" @click="submitForm('form')">提交</el-button>
              </el-form-item>
            </div>
          </el-tab-pane>
          <el-tab-pane label="地址管理" name="address" v-if="form.id">
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
              <tbody v-if="addresses.length">
                <tr v-for="address, index in addresses" :key="index">
                  <td>@{{ index }}</td>
                  <td>@{{ address.name }}</td>
                  <td>@{{ address.phone }}</td>
                  <td>222</td>
                  <td>222</td>
                  <td>
                    <button class="btn btn-outline-secondary btn-sm" type="button"
                      @click="editAddress(index)">编辑</button>
                    <button class="btn btn-outline-danger btn-sm ml-1" type="button"
                      @click="deleteAddress(address.id, index)">删除</button>
                  </td>
              </tbody>
              <tbody v-else>
                <tr>
                  <td colspan="6" class="text-center">
                    <span class="me-2">当前账号无地址</span>
                    <el-link type="primary" @click="editAddress">新增地址</el-link>
                  </td>
              </tbody>
            </table>
          </el-tab-pane>
        </el-tabs>
      </el-form>
    </div>

    <el-dialog title="编辑地址" :visible.sync="dialogAddress.show" width="600px"
      @close="closeAddressDialog('addressForm')">
      <el-form ref="addressForm" :rules="addressRules" :model="dialogAddress.form" label-width="100px">
        <el-form-item label="姓名" prop="name">
          <el-input v-model="dialogAddress.form.name"></el-input>
        </el-form-item>
        <el-form-item label="联系电话" prop="phone">
          <el-input maxlength="11" v-model="dialogAddress.form.phone"></el-input>
        </el-form-item>
        <el-form-item label="地址" required>
          <div class="row">
            <div class="col-4">
              <el-form-item>
                <el-select v-model="dialogAddress.form.country_id" filterable placeholder="选择国家" @change="countryChange">
                  <el-option v-for="item in source.countries" :key="item.id" :label="item.name"
                    :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>
            </div>
            <div class="col-4">
              <el-form-item prop="zone_id">
                <el-select v-model="dialogAddress.form.zone_id" filterable placeholder="选择省份">
                  <el-option v-for="item in source.zones" :key="item.id" :label="item.name"
                    :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>
            </div>
            <div class="col-4">
              <el-form-item prop="city_id">
                <el-input v-model="dialogAddress.form.city_id" placeholder="输入 city"></el-input>
              </el-form-item>
            </div>
          </div>
        </el-form-item>
        <el-form-item label="邮编" prop="zipcode">
          <el-input v-model="dialogAddress.form.zipcode"></el-input>
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
          id: @json($customer['id'] ?? null),
          name: @json($customer['name']),
          email: @json($customer['email']),
          password: '',
          customer_group_id: 1,
          status: @json($customer['status']) * 1,
        },

        addresses: @json($customer['addresses'] ?? []),

        source: {
          customer_group: @json($customer_groups ?? []),
          countries: @json($countries ?? []),
          zones: []
        },

        dialogAddress: {
          show: false,
          index: null,
          form: {
            name: '',
            phone: '',
            country_id: @json((int)system_setting('base.country_id')),
            zipcode: '',
            zone_id: '',
            city_id: '',
            address_1: '',
            address_2: '',
          }
        },

        rules: {
          name: [{required: true, message: '请输入用户名', trigger: 'blur'}, ],
          email: [
            {required: true, message: '请输入邮箱', trigger: 'blur'},
            {type: 'email', message: '请输入正确邮箱格式' ,trigger: 'blur'},
          ],
        },

        addressRules: {
          name: [{
            required: true,
            message: '请输入姓名',
            trigger: 'blur'
          }, ],
          phone: [{
            required: true,
            message: '请输入联系电话',
            trigger: 'blur'
          }, ],
          address_1: [{
            required: true,
            message: '请输入详细地址 1',
            trigger: 'blur'
          }, ],
          zone_id: [{
            required: true,
            message: '请选择省份',
            trigger: 'blur'
          }, ],
          city_id: [{
            required: true,
            message: '请填写 city',
            trigger: 'blur'
          }, ],
        }
      },

      // 在挂载开始之前被调用:相关的 render 函数首次被调用
      beforeMount() {
        this.countryChange(this.dialogAddress.form.country_id);
      },

      methods: {
        submitForm(form) {
          const self = this;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            $.ajax({
              url: `/admin/customers/{{ $customer['id'] }}`,
              type: 'put',
              data: self.form,
              success: function(res) {
                self.$message.success(res.message);
              }
            })
          });
        },

        editAddress(index) {
          if (typeof index == 'number') {
            this.dialogAddress.index = index;
            this.dialogAddress.form = JSON.parse(JSON.stringify(this.addresses[index]))
          }

          this.dialogAddress.show = true
        },

        deleteAddress(id, index) {
          const self = this;

          $.ajax({
            url: `/admin/customers/{{ $customer['id'] }}/addresses/${id}`,
            type: 'delete',
            success: function(res) {
              self.$message.success(res.message);
              self.addresses.splice(index, 1)
            }
          })
        },

        addressFormSubmit(form) {
          const self = this;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            const type = this.dialogAddress.form.id ? 'put' : 'post';

            $.ajax({
              url: `/admin/customers/{{ $customer['id'] }}/addresses${type == 'put' ? '/' + this.dialogAddress.form.id : ''}`,
              data: self.dialogAddress.form,
              type: type,
              success: function(res) {
                if (type == 'post') {
                  self.addresses.push(res.data)
                } else {
                  self.addresses[self.dialogAddress.index] = res.data
                }
                self.$message.success(res.message);
                self.$refs[form].resetFields();
                self.dialogAddress.show = false
                self.dialogAddress.index = null;
              }
            })
          });
        },

        closeAddressDialog(form) {
          this.$refs[form].resetFields();
          this.dialogAddress.show = false
          this.dialogAddress.index = null;
        },

        countryChange(e) {
          $http.get(`countries/${e}/zones`).then((res) => {
            this.source.zones = res.data.zones;
          })
        }
      }
    });
  </script>
@endpush
