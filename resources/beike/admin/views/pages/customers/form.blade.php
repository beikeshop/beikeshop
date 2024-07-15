@extends('admin::layouts.master')

@section('title', __('admin/common.customer'))

@section('content')
  <div id="customer-app-form" class="card" v-cloak>
    <div class="card-body">
      <el-form :model="form" :rules="rules" ref="form" label-width="140px">
        <el-tabs v-model="customerTab">
          <el-tab-pane label="{{ __('admin/customer.user_info') }}" name="customer">
            <div class="form-max-w">
              <el-form-item label="{{ __('admin/customer.user_name') }}" prop="name">
                <el-input v-model="form.name" placeholder="{{ __('admin/customer.user_name') }}"></el-input>
              </el-form-item>
              <el-form-item label="{{ __('common.email') }}" prop="email">
                <el-input v-model="form.email" placeholder="{{ __('common.email') }}"></el-input>
              </el-form-item>
              <el-form-item label="{{ __('shop/login.password') }}" prop="password">
                <el-input v-model="form.password" placeholder="{{ __('admin/customer.password_info') }}"></el-input>
              </el-form-item>
              @hookwrapper('admin.customer.edit.from.customer_group')
              <el-form-item label="{{ __('admin/customer_group.index') }}">
                <el-select v-model="form.customer_group_id" placeholder="请选择">
                  <el-option v-for="item in source.customer_group" :key="item.id" :label="item.name"
                    :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>
              @endhookwrapper
              <el-form-item label="{{ __('common.status') }}" prop="active">
                <el-switch v-model="form.active" :active-value="1" :inactive-value="0"></el-switch>
              </el-form-item>
              <el-form-item label="{{ __('common.examine') }}" prop="examine">
                <el-select v-model="form.status" placeholder="请选择">
                  <el-option v-for="item in source.statuses" :key="item.code" :label="item.label"
                    :value="item.code">
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" @click="submitForm('form')">{{ __('common.submit') }}</el-button>
              </el-form-item>
            </div>
          </el-tab-pane>
          <el-tab-pane label="{{ __('admin/customer.address_management') }}" name="address" v-if="form.id">
            <button class="btn btn-primary mb-3" type="button" @click="editAddress">{{ __('common.add') }}</button>
            <div class="table-push">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>{{ __('address.name') }}</th>
                    <th>{{ __('common.phone') }}</th>
                    <th>{{ __('common.created_at') }}</th>
                    <th>{{ __('common.action') }}</th>
                  </tr>
                </thead>
                <tbody v-if="addresses.length">
                  <tr v-for="address, index in addresses" :key="index">
                    <td>@{{ index + 1}}</td>
                    <td>@{{ address.name }}</td>
                    <td>@{{ address.phone }}</td>
                    <td>@{{ address.created_at }}</td>
                    <td>
                      <button class="btn btn-outline-secondary btn-sm" type="button"
                        @click="editAddress(index)">{{ __('common.edit') }}</button>
                      <button class="btn btn-outline-danger btn-sm ml-1" type="button"
                        @click="deleteAddress(address.id, index)">{{ __('common.delete') }}</button>
                    </td>
                </tbody>
                <tbody v-else>
                  <tr>
                    <td colspan="6" class="text-center">
                      <span class="me-2">{{ __('admin/customer.no_address') }}</span>
                      <el-link type="primary" @click="editAddress">{{ __('admin/customer.add_address') }}</el-link>
                    </td>
                </tbody>
              </table>
            </div>
          </el-tab-pane>
        </el-tabs>
      </el-form>
    </div>

    <el-dialog title="{{ __('admin/customer.edit_address') }}" :visible.sync="dialogAddress.show" width="650px"
      @close="closeAddressDialog('addressForm')">
      <el-form ref="addressForm" :rules="addressRules" :model="dialogAddress.form" label-width="100px">
        <el-form-item label="{{ __('address.name') }}" prop="name">
          <el-input v-model="dialogAddress.form.name"></el-input>
        </el-form-item>
        <el-form-item label="{{ __('common.phone') }}" prop="phone">
          <el-input maxlength="11" v-model="dialogAddress.form.phone"></el-input>
        </el-form-item>
        <el-form-item label="{{ __('admin/customer.address') }}" required>
          <div class="row">
            <div class="col-4">
              <el-form-item>
                <el-select v-model="dialogAddress.form.country_id" filterable placeholder="{{ __('admin/customer.choose_country') }}" @change="countryChange">
                  <el-option v-for="item in source.countries" :key="item.id" :label="item.name"
                    :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>
            </div>
            <div class="col-4">
              <el-form-item prop="zone_id">
                <el-select v-model="dialogAddress.form.zone_id" filterable placeholder="{{ __('admin/customer.choose_zones') }}">
                  <el-option v-for="item in source.zones" :key="item.id" :label="item.name"
                    :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>
            </div>
            <div class="col-4">
              <el-form-item prop="city">
                <el-input v-model="dialogAddress.form.city" placeholder="{{ __('admin/customer.enter_city') }}"></el-input>
              </el-form-item>
            </div>
          </div>
        </el-form-item>
        <el-form-item label="{{ __('admin/customer.zipcode') }}" prop="zipcode">
          <el-input v-model="dialogAddress.form.zipcode"></el-input>
        </el-form-item>
        <el-form-item label="{{ __('admin/customer.address_1') }}" prop="address_1">
          <el-input v-model="dialogAddress.form.address_1"></el-input>
        </el-form-item>
        <el-form-item label="{{ __('admin/customer.address_2') }}">
          <el-input v-model="dialogAddress.form.address_2"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="addressFormSubmit('addressForm')">{{ __('common.save') }}</el-button>
          <el-button @click="closeAddressDialog('addressForm')">{{ __('common.cancel') }}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>

  @hook('admin.customer.form.footer')
@endsection

@push('footer')
  <script>
    let app = new Vue({
      el: '#customer-app-form',
      data: {
        customerTab: 'customer',
        form: {
          id: @json($customer['id'] ?? null),
          name: @json($customer['name']),
          email: @json($customer['email']),
          password: '',
          customer_group_id: @json($customer['customer_group_id']),
          active: @json($customer['active']),
          status: @json($customer['status']),
        },

        addresses: @json($customer['addresses'] ?? []),

        source: {
          customer_group: @json($customer_groups ?? []),
          countries: @json($countries ?? []),
          statuses: @json($statuses ?? []),
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
            city: '',
            city_id: '',
            address_1: '',
            address_2: '',
          }
        },

        rules: {
          name: [{required: true, message: "{{ __('common.error_required', ['name' => __('admin/customer.user_name')] ) }}", trigger: 'blur'}, ],
          email: [
            {required: true, message: '{{ __('common.error_required', ['name' => __('common.email')] ) }}', trigger: 'blur'},
            {type: 'email', message: '{{ __('common.error_required', ['name' => __('admin/customer.error_email')] ) }}' ,trigger: 'blur'},
          ],
        },

        addressRules: {
          name: [{
            required: true,
            message: '{{ __('common.error_required', ['name' => __('admin/customer.user_name')] ) }}',
            trigger: 'blur'
          }, ],
          phone: [{
            required: true,
            message: '{{ __('common.error_required', ['name' => __('common.phone')] ) }}',
            trigger: 'blur'
          }, ],
          address_1: [{
            required: true,
            message: '{{ __('common.error_required', ['name' => __('admin/customer.address_1')] ) }}',
            trigger: 'blur'
          }, ],
          zone_id: [{
            required: true,
            message: '{{ __('common.error_required', ['name' => __('admin/customer.zones')] ) }}',
            trigger: 'blur'
          }, ],
          city: [{
            required: true,
            message: '{{ __('common.error_required', ['name' => __('admin/customer.city')] ) }}',
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
              this.$message.error('{{__('common.error_form')}}');
              return;
            }

            $http.put(`customers/{{ $customer['id'] }}`, self.form).then((res) => {
              layer.msg(res.message);
                location = '{{ admin_route("customers.index") }}'
            })
          });
        },

        editAddress(index) {
          if (typeof index == 'number') {
            this.dialogAddress.index = index;
            this.dialogAddress.form = JSON.parse(JSON.stringify(this.addresses[index]))
            this.countryChange(this.dialogAddress.form.country_id);
          }

          this.countryChange(this.dialogAddress.form.country_id);
          this.dialogAddress.show = true
        },

        countryChange(e) {
          const self = this;

          $http.get(`countries/${e}/zones`, null, {
            hload: true
          }).then((res) => {
            this.source.zones = res.data.zones;

            if (!res.data.zones.some(e => e.id == this.form.zone_id)) {
              this.form.zone_id = '';
            }
          })
        },

        deleteAddress(id, index) {
          this.$confirm('{{ __('admin/customer.confirm_delete_address') }}', '{{__('common.text_hint')}}', {
            confirmButtonText: '{{__('common.confirm')}}',
            cancelButtonText: '{{__('common.cancel')}}',
            type: 'warning'
          }).then(() => {
            $http.delete(`customers/{{ $customer['id'] }}/addresses/${id}`).then((res) => {
              this.$message.success(res.message);
              this.addresses.splice(index, 1)
            })
          }).catch(()=>{})
        },

        addressFormSubmit(form) {
          const self = this;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{__('common.error_form')}}');
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
          Object.keys(this.dialogAddress.form).forEach(key => this.dialogAddress.form[key] = '')
          this.dialogAddress.form.country_id = @json((int)system_setting('base.country_id'));
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

    @hook('admin.customer.form.js.after')
  </script>
@endpush
