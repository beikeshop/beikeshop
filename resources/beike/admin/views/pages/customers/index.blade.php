@extends('admin::layouts.master')

@section('title', __('admin/common.customer'))

@section('content')
  <div id="customer-app" class="card" v-cloak>
    <div class="card-body">
      <div class="bg-light p-4 mb-3">
        <el-form :inline="true" :model="filter" class="demo-form-inline" label-width="100px">
          <div>
            <el-form-item label="{{ __('customer.name') }}">
              <el-input @keyup.enter.native="search" v-model="filter.name" size="small" placeholder="{{ __('customer.name') }}"></el-input>
            </el-form-item>
            <el-form-item label="{{ __('customer.email') }}">
              <el-input @keyup.enter.native="search" v-model="filter.email" size="small" placeholder="{{ __('customer.email') }}"></el-input>
            </el-form-item>
            <el-form-item label="{{ __('customer.customer_group') }}">
              <el-select size="small" v-model="filter.customer_group_id" placeholder="{{ __('common.please_choose') }}">
                <el-option v-for="item in source.customer_group" :key="item.id" :label="item.name"
                  :value="item.id + ''">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="{{ __('common.status') }}">
              <el-select size="small" v-model="filter.status" placeholder="{{ __('common.please_choose') }}">
                <el-option label="{{ __('common.all') }}" value=""></el-option>
                <el-option label="{{ __('common.enabled') }}" value="1"></el-option>
                <el-option label="{{ __('common.disabled') }}" value="0"></el-option>
              </el-select>
            </el-form-item>
          </div>
        </el-form>

        <div class="row">
          <label class="wp-100"></label>
          <div class="col-auto">
            <button type="button" @click="search" class="btn btn-outline-primary btn-sm">{{ __('common.filter') }}</button>
            <button type="button" @click="resetSearch" class="btn btn-outline-secondary btn-sm ms-1">{{ __('common.reset') }}</button>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCustomersCreate">{{ __('admin/customer.customers_create') }}</button>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>{{ __('common.id') }}</th>
            <th>{{ __('customer.email') }}</th>
            <th>{{ __('customer.name') }}</th>
            <th>{{ __('customer.from') }}</th>
            <th>{{ __('customer.customer_group') }}</th>
            <th>{{ __('common.status') }}</th>
              <th>{{ __('common.created_at') }}</th>
              <th>{{ __('common.action') }}</th>
          </tr>
        </thead>
        <tbody v-if="customers.data.length">
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
            <td>@{{ customer.created_at }}</td>
            <td>
              <a class="btn btn-outline-secondary btn-sm" :href="customer.edit">{{ __('common.edit') }}</a>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(customer.delete, index)">{{ __('common.delete') }}</button>
            </td>
          </tr>
        </tbody>
        <tbody v-else><tr><td colspan="9" class="border-0"><x-admin-no-data /></td></tr></tbody>
      </table>

      <el-pagination v-if="customers.data.length" layout="prev, pager, next" background :page-size="customers.per_page" :current-page.sync="page"
        :total="customers.total"></el-pagination>
    </div>

    <el-dialog title="{{ __('admin/customer.customers_create') }}" :visible.sync="dialogCustomers.show" width="670px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">
      <el-form ref="form" :rules="rules" :model="dialogCustomers.form" label-width="120px">
        <el-form-item label="{{ __('admin/customer.user_name') }}" prop="name">
          <el-input v-model="dialogCustomers.form.name" placeholder="{{ __('admin/customer.user_name') }}"></el-input>
        </el-form-item>
        <el-form-item label="{{ __('customer.email') }}" prop="email">
          <el-input v-model="dialogCustomers.form.email" placeholder="{{ __('customer.email') }}"></el-input>
        </el-form-item>
        <el-form-item label="{{ __('shop/login.password') }}" prop="password">
          <el-input v-model="dialogCustomers.form.password" placeholder="{{ __('shop/login.password') }}"></el-input>
        </el-form-item>
        <el-form-item label="{{ __('customer.customer_group') }}">
          <el-select v-model="dialogCustomers.form.customer_group_id" placeholder="">
            <el-option v-for="item in source.customer_group" :key="item.id" :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="{{ __('common.status') }}" prop="status">
          <el-switch v-model="dialogCustomers.form.status" :active-value="1" :inactive-value="0"></el-switch>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="addCustomersFormSubmit('form')">{{ __('common.save') }}</el-button>
          <el-button @click="closeCustomersDialog('form')">{{ __('common.cancel') }}</el-button>
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
          name: [{required: true,message: '{{ __('common.error_required', ['name' => __('admin/customer.user_name')] ) }}', trigger: 'blur'}, ],
          email: [
            {required: true, message: '{{ __('common.error_required', ['name' => __('common.email')] ) }}', trigger: 'blur'},
            {type: 'email', message: '{{ __('admin/customer.error_email') }}' ,trigger: 'blur'},
          ],
          password: [{required: true,message: '{{ __('common.error_required', ['name' => __('shop/login.password')] ) }}',trigger: 'blur'}, ],
        },

        url: @json(admin_route('customers.index')),

        filter: {
          email: bk.getQueryString('email'),
          name: bk.getQueryString('name'),
          customer_group_id: bk.getQueryString('customer_group_id'),
          status: bk.getQueryString('status'),
        },
      },

      mounted () {
      },

      watch: {
        page: function() {
          this.loadData();
        },
      },

      computed: {
        query() {
          let query = '';
          const filter = Object.keys(this.filter)
            .filter(key => this.filter[key])
            .map(key => key + '=' + this.filter[key])
            .join('&');

          if (filter) {
            query += '?' + filter;
          }

          return query;
        }
      },

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
              this.$message.error('{{ __('common.error_form') }}');
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
          this.$confirm('{{ __('common.confirm_delete') }}', '{{ __('common.text_hint') }}', {
            confirmButtonText: '{{ __('common.confirm') }}',
            cancelButtonText: '{{ __('common.cancel') }}',
            type: 'warning'
          }).then(() => {
            $http.delete(url).then((res) => {
              self.$message.success(res.message);
              window.location.reload();
              // self.customers.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          this.$refs[form].resetFields();
          this.dialogCustomers.show = false
        },

        search() {
          location = this.url + this.query
        },

        resetSearch() {
          Object.keys(this.filter).forEach(key => this.filter[key] = '')
          location = this.url + this.query
        },
      }
    })
  </script>
@endpush
