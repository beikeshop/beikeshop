@extends('admin::layouts.master')

@section('title', __('admin/attribute_group.index'))

@section('content')
  <div id="customer-app" class="card h-min-600" v-cloak>
    <div class="card-body">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCustomersCreate('add', null)">{{ __('admin/attribute_group.create_at_groups') }}</button>
      </div>
      <div class="table-push">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>{{ __('common.name') }}</th>
              <th>{{ __('common.created_at') }}</th>
              <th width="130px">{{ __('common.action') }}</th>
            </tr>
          </thead>
          <tbody v-if="attribute_groups.length">
            <tr v-for="group, index in attribute_groups" :key="index">
              <td>@{{ group.id }}</td>
              <td>@{{ group.description?.name || '' }}</td>
              <td>@{{ group.created_at }}</td>
              <td>
                <button class="btn btn-outline-secondary btn-sm" @click="checkedCustomersCreate('edit', index)">{{ __('common.edit') }}</button>
                <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(group.id, index)">{{ __('common.delete') }}</button>
              </td>
            </tr>
          </tbody>
          <tbody v-else><tr><td colspan="9" class="border-0"><x-admin-no-data /></td></tr></tbody>
        </table>
      </div>
    </div>

    <el-dialog title="{{ __('admin/attribute_group.index') }}" :visible.sync="dialog.show" width="670px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="155px">
        <el-form-item label="{{ __('common.name') }}" required class="language-inputs">
          <el-form-item  :prop="'name.' + lang.code" :inline-message="true"  v-for="lang, lang_i in source.languages" :key="lang_i"
            :rules="[
              { required: true, message: '{{ __('common.error_required', ['name' => __('common.name')]) }}', trigger: 'blur' },
            ]"
          >
            <el-input size="mini" v-model="dialog.form.name[lang.code]" placeholder="{{ __('common.name') }}"><template slot="prepend">@{{lang.name}}</template></el-input>
          </el-form-item>
          
          @hook('admin.product.attributes.group.edit.dialog.name.after')

        </el-form-item>

        <el-form-item label="{{ __('common.sort_order') }}" prop="sort_order">
          <el-input class="mb-0" v-model="dialog.form.sort_order" type="number" placeholder="{{ __('common.sort_order') }}"></el-input>
        </el-form-item>

        <el-form-item>
          <div class="d-flex d-lg-block">
            <el-button type="primary" @click="addCustomersFormSubmit('form')">{{ __('common.save') }}</el-button>
            <el-button @click="closeCustomersDialog('form')">{{ __('common.cancel') }}</el-button>
          </div>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
@endsection

@push('footer')
  <script>
    let app = new Vue({
      el: '#customer-app',

      data: {
        attribute_groups: @json($attribute_groups ?? []),

        source: {
          languages: @json(locales()),
        },

        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            id: null,
            name: {},
            sort_order: 0,
          },
        },

        rules: {
          sort_order: [{required: true,message: '{{ __('common.error_required', ['name' => __('common.sort_order')])}}',trigger: 'blur'}, ],
        }
      },

      methods: {
        checkedCustomersCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index

          if (type == 'edit') {
            let group = this.attribute_groups[index];

            this.dialog.form = {
              id: group.id,
              name: {},
              sort_order: group.sort_order,
            }

            group.descriptions.forEach((e, index) => {
              this.$set(this.dialog.form.name, e.locale, e.name)
            })
          }
        },

        addCustomersFormSubmit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'attribute_groups' : 'attribute_groups/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{ __('common.error_form') }}');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.type == 'add') {
                this.attribute_groups.unshift(res.data)
              } else {
                this.attribute_groups[this.dialog.index] = res.data
              }
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
            $http.delete('attribute_groups/' + id).then((res) => {
              if (res.status == 'fail') {
                layer.msg(res.message,()=>{})
                return;
              }

              layer.msg(res.message)
              self.attribute_groups.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          this.$refs[form].resetFields();
          this.dialog.form.name = {};
          this.dialog.form.description = {};
          this.dialog.show = false
        }
      }
    })
  </script>
@endpush
