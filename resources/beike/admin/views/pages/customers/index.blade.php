@extends('admin::layouts.master')

@section('title', __('admin/common.customer'))

@section('content')
  <div id="customer-app" class="card h-min-600" v-cloak>
    <div class="card-body">
      <div class="bg-light p-4 mb-3">
        <el-form :inline="true" :model="filter" class="demo-form-inline" label-width="70px">
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

            @hook('admin.customer.list.filter')
          </div>
        </el-form>

        <div class="row">
          <label style="width: 70px"></label>
          <div class="col-auto">
            <button type="button" @click="search" class="btn btn-outline-primary btn-sm">{{ __('common.filter') }}</button>
            <button type="button" @click="resetSearch" class="btn btn-outline-secondary btn-sm ms-1">{{ __('common.reset') }}</button>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-between mb-4">
        @if ($type != 'trashed')
          <button type="button" class="btn btn-primary" @click="checkedCustomersCreate">{{ __('admin/customer.customers_create') }}</button>
        @else
          <button type="button" class="btn btn-primary" @click="checkedCustomerSclearRestore">{{ __('admin/product.clear_restore') }}</button>
        @endif
      </div>

      @if ($customers->total())
        <div class="table-push">
          <table class="table">
            <thead>
              <tr>
                <th>{{ __('common.id') }}</th>
                <th>{{ __('customer.email') }}</th>
                <th>{{ __('customer.name') }}</th>
                <th>{{ __('customer.from') }}</th>
                <th>{{ __('customer.customer_group') }}</th>
                @if ($type != 'trashed')
                <th>{{ __('common.status') }}</th>
                <th>{{ __('common.examine') }}</th>
                @endif
                <th>{{ __('common.created_at') }}</th>
                @hook('admin.customer.list.column')
                <th>{{ __('common.action') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($customers as $customer)
              <tr data-item='@json($customer)'>
                <td>{{ $customer['id'] }}</td>
                <td>{{ $customer['email'] }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <div>{{ $customer['name'] }}</div>
                  </div>
                </td>
                <td>{{ $customer['from'] }}</td>
                <td>{{ $customer->customerGroup->description->name ?? '' }}</td>
                @if ($type != 'trashed')
                <td>
                  <div class="form-check form-switch">
                    <input class="form-check-input cursor-pointer" type="checkbox" role="switch" data-active="{{ $customer['active'] ? 1 : 0 }}" data-id="{{ $customer['id'] }}" @change="turnOnOff($event)" {{ $customer['active'] ? 'checked' : '' }}>
                  </div>
                </td>
                <td>
                  <select class="form-select customer-status form-select-sm" data-id="{{ $customer['id'] }}" style="max-width: 100px">
                    @foreach ($statuses as $status)
                      <option value="{{ $status['code'] }}" {{ $status['code'] == $customer['status'] ? 'selected' : '' }}>
                        {{ $status['label'] }}
                      </option>
                      @endforeach
                  </select>
                </td>
                @endif
                <td>{{ $customer['created_at'] }}</td>
                @hook('admin.customer.list.column_value')
                <td>
                  @if ($type != 'trashed')
                    <a class="btn btn-outline-secondary btn-sm" href="{{ admin_route('customers.edit', [$customer->id]) }}">{{ __('common.edit') }}</a>
                    <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer({{ $customer['id'] }})">{{ __('common.delete') }}</button>
                    @hook('admin.customer.list.action')
                  @else
                    <a href="javascript:void(0)" class="btn btn-outline-secondary btn-sm"
                    @click.prevent="restore({{ $customer['id'] }})">{{ __('common.restore') }}</a>
                    <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteTrashedCustomer({{ $customer['id'] }})">{{ __('common.delete') }}</button>
                    @hook('admin.customer.trashed.action')
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $customers->withQueryString()->links('admin::vendor/pagination/bootstrap-4') }}
      @else
        <x-admin-no-data />
      @endif
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

  @hook('admin.customer.list.content.footer')
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#customer-app',

      data: {
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

        url: '{{ $type == 'trashed' ? admin_route('customers.trashed') : admin_route('customers.index') }}',

        filter: {
          page: bk.getQueryString('page'),
          email: bk.getQueryString('email'),
          name: bk.getQueryString('name'),
          customer_group_id: bk.getQueryString('customer_group_id'),
          status: bk.getQueryString('status'),
        },

        customerIds: @json($customers->pluck('id')),
      },

      created() {
        bk.addFilterCondition(this);
      },

      methods: {
        turnOnOff() {
          let id = event.currentTarget.getAttribute("data-id");
          let checked = event.currentTarget.getAttribute("data-active");
          let type = 1;
          if (checked * 1) {
            type = 0;
          }
          $http.put(`customers/${id}/update_active`, {active: type}).then((res) => {
            layer.msg(res.message)
            location.reload();
          })
        },

        checkedCustomersCreate() {
          this.dialogCustomers.show = true
        },

        deleteTrashedCustomer(id) {
          this.$confirm('{{ __('common.confirm_delete') }}', '{{ __('common.text_hint') }}', {
            confirmButtonText: '{{ __('common.confirm') }}',
            cancelButtonText: '{{ __('common.cancel') }}',
            type: 'warning'
          }).then(() => {
            $http.delete('customers/' + id + '/force').then((res) => {
              this.$message.success(res.message);
              window.location.reload();
            })
          }).catch(()=>{})
        },

        // 清空回收站
        checkedCustomerSclearRestore() {
          this.$confirm('{{ __('admin/product.confirm_delete_restore') }}', '{{ __('common.text_hint') }}', {
            confirmButtonText: '{{ __('common.confirm') }}',
            cancelButtonText: '{{ __('common.cancel') }}',
            type: 'warning'
          }).then(() => {
            $http.post('{{ admin_route('customers.force_delete_all') }}').then((res) => {
              this.$message.success(res.message);
              window.location.reload();
            })
          }).catch(()=>{})
        },

        restore(id, index) {
          $http.delete('customers/' + id + '/restore').then((res) => {
            this.$message.success(res.message);
            window.location.reload();
          })
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
              window.location.reload();
              this.dialogCustomers.show = false
            })
          });
        },

        deleteCustomer(id) {
          const self = this;
          this.$confirm('{{ __('common.confirm_delete') }}', '{{ __('common.text_hint') }}', {
            confirmButtonText: '{{ __('common.confirm') }}',
            cancelButtonText: '{{ __('common.cancel') }}',
            type: 'warning'
          }).then(() => {
            $http.delete(`customers/${id}`).then((res) => {
              self.$message.success(res.message);
              window.location.reload();
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          this.$refs[form].resetFields();
          this.dialogCustomers.show = false
        },

        search() {
          location = bk.objectToUrlParams(this.filter, this.url)
        },

        resetSearch() {
          this.filter = bk.clearObjectValue(this.filter)
          location = bk.objectToUrlParams(this.filter, this.url)
        },
      }
    })

    $('.customer-status').change(function(event) {
    const id = $(this).data('id');
    const status = $(this).val();
    const self = $(this);
    $http.put(`customers/${id}/update_status`, {status: status}).then((res) => {
      layer.msg('修改状态成功');
    })
  });
  </script>
@endpush
