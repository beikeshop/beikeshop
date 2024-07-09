@extends('admin::layouts.master')

@section('title', __('admin/common.brand'))

@section('content')
  <div id="customer-app" class="card h-min-600" v-cloak>
    <div class="card-body">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCreate('add', null)">{{ __('admin/brand.brands_create') }}</button>
      </div>
      <div class="table-push">
        <table class="table">
          <thead>
            <tr>
              <th>{{ __('common.id') }}</th>
              <th>{{ __('brand.name') }}</th>
              <th>{{ __('brand.icon') }}</th>
              <th>{{ __('common.sort_order') }}</th>
              <th>{{ __('brand.first_letter') }}</th>
              <th>{{ __('common.status') }}</th>
              <th>{{ __('common.action') }}</th>
            </tr>
          </thead>
          <tbody v-if="brands.data.length">
            <tr v-for="brand, index in brands.data" :key="index">
              <td>@{{ brand.id }}</td>
              <td>@{{ brand.name }}</td>
              <td><div class="wh-50 border d-flex justify-content-center rounded-2 align-items-center"><img :src="thumbnail(brand.logo)" class="img-fluid rounded-2"></div></td>
              <td>@{{ brand.sort_order }}</td>
              <td>@{{ brand.first }}</td>
              <td>
                <span class="text-success" v-if="brand.status">{{ __('common.enabled') }}</span>
                <span class="text-secondary" v-else>{{ __('common.disabled') }}</span>
              </td>
              <td>
                <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">{{ __('common.edit') }}</button>
                <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteItem(brand.id, index)">{{ __('common.delete') }}</button>
              </td>
            </tr>
          </tbody>
          <tbody v-else><tr><td colspan="7" class="border-0"><x-admin-no-data /></td></tr></tbody>
        </table>
      </div>

      <el-pagination v-if="brands.data.length" layout="prev, pager, next" background :page-size="brands.per_page" :current-page.sync="page"
        :total="brands.total" :current-page.sync="page"></el-pagination>
    </div>

    <el-dialog title="{{ __('admin/common.brand') }}" :visible.sync="dialog.show" width="600px"
      @close="closeDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        @hook('admin.brand.form.before')

        <el-form-item label="{{ __('brand.name') }}" prop="name">
          <el-input class="mb-0" v-model="dialog.form.name" placeholder="{{ __('brand.name') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('brand.icon') }}" prop="logo" required>
          <vue-image v-model="dialog.form.logo"></vue-image>
        </el-form-item>

        <el-form-item label="{{ __('brand.first_letter') }}" prop="first">
          <el-input class="mb-0" :maxlength="1" v-model="dialog.form.first" placeholder="{{ __('brand.first_letter') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('common.sort_order') }}">
          <el-input class="mb-0" type="number" v-model="dialog.form.sort_order" placeholder="{{ __('common.sort_order') }}"></el-input>
        </el-form-item>

        @hook('admin.brand.form.after')

        <el-form-item label="{{ __('common.status') }}">
          <el-switch v-model="dialog.form.status" :active-value="1" :inactive-value="0"></el-switch>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="submit('form')">{{ __('common.save') }}</el-button>
          <el-button @click="dialog.show = false">{{ __('common.cancel') }}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
@endsection



@push('footer')
  @include('admin::shared.vue-image')
  <script>
    new Vue({
      el: '#customer-app',

      data: {
        brands: @json($brands ?? []),
        page: bk.getQueryString('page', 1) * 1,
        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            id: null,
            name: '',
            logo: '',
            first: '',
            sort_order: '',
            status: 1,
          },
        },

        rules: {
          name: [{required: true,message: '{{ __('common.error_required', ['name' => __('common.name')])}}',trigger: 'blur'}, ],
          first: [{required: true,message: '{{ __('common.error_required', ['name' => __('brand.first_letter')])}}',trigger: 'blur'}, ],
          logo: [{required: true,message: '{{ __('admin/brand.error_upload') }}',trigger: 'change'}, ],
        }
      },

      watch: {
        page: function() {
          this.loadData();
        },
      },

      mounted() {
        bk.ajaxPageReloadData(this)
      },

      computed: {
        url() {
          const url = @json(admin_route('brands.index'));

          if (this.page) {
            return url + '?page=' + this.page;
          }

          return url;
        },
      },

      methods: {
        loadData() {
          window.history.pushState('', '', this.url);
          $http.get(`brands?page=${this.page}`).then((res) => {
            this.brands = res.data.brands;
          })
        },

        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index

          if (type == 'edit') {
            let item = JSON.parse(JSON.stringify(this.brands.data[index]));
            item.status = Number(item.status)
            this.dialog.form = item
          }
        },

        submit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'brands' : 'brands/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{ __('common.error_form') }}');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);

              if (this.dialog.type == 'add') {
                this.brands.data.unshift(res.data)
              } else {
                this.brands.data[this.dialog.index] = res.data
              }
              this.dialog.show = false
              this.loadData()
            })
          });
        },

        deleteItem(id, index) {
          const self = this;
          this.$confirm('{{ __('common.confirm_delete') }}', '{{ __('common.text_hint') }}', {
            confirmButtonText: '{{ __('common.confirm') }}',
            cancelButtonText: '{{ __('common.cancel') }}',
            type: 'warning'
          }).then(() => {
            $http.delete('brands/' + id).then((res) => {
              this.$message.success(res.message);
              self.brands.data.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeDialog(form) {
          this.$refs[form].resetFields();
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.form.status = 1
        }
      }
    })
  </script>
@endpush
