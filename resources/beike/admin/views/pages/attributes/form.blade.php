@extends('admin::layouts.master')

@section('title', __('admin/attribute.index'))

@section('content')
  <div id="customer-app-form" class="card" v-cloak>
    <div class="card-body h-min-600">
      @hook('admin.attribute.form.content.before')

      <el-form :model="form" :rules="rules" ref="form" label-width="140px">
        <el-tabs v-model="customerTab">
          @hook('admin.attribute.form.tabs.before')
          <el-tab-pane label="{{ __('admin/attribute.attribute_info') }}" name="customer">
            <div class="form-max-w">
              @hook('admin.attribute.form.tabs.customer.before')
              <el-form-item label="{{ __('common.name') }}" required class="language-inputs">
                <el-form-item :prop="'name.' + lang.code" :inline-message="true"  v-for="lang, lang_i in source.languages" :key="lang_i"
                  :rules="[
                    { required: true, message: '{{ __('common.error_required', ['name' => __('common.name')]) }}', trigger: 'blur' },
                  ]"
                >
                  <el-input size="mini" v-model="form.name[lang.code]" placeholder="{{ __('common.name') }}"><template slot="prepend">@{{lang.name}}</template></el-input>
                </el-form-item>

                @hook('admin.product.attributes.edit.name.after')

              </el-form-item>

              <el-form-item label="{{ __('admin/attribute_group.index') }}" required prop="attribute_group_id">
                <el-select v-model="form.attribute_group_id" placeholder="{{ __('common.please_choose') }}">
                  <el-option
                    v-for="item in source.attributeGroup"
                    :key="item.id"
                    :label="item.description?.name || ''"
                    :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>

              <el-form-item label="{{ __('common.sort_order') }}" prop="sort_order">
                <el-input v-model="form.sort_order" style="width: 189px;" placeholder="{{ __('common.sort_order') }}"></el-input>
              </el-form-item>

              @hook('admin.attribute.form.tabs.customer.after')

              <el-form-item>
                @hook('admin.attribute.form.tabs.customer.submit.before')
                <el-button type="primary" class="mt-5" @click="submitForm('form')">{{ __('common.submit') }}</el-button>
                @hook('admin.attribute.form.tabs.customer.submit.after')
              </el-form-item>

              @hook('admin.attribute.form.tabs.customer.after')
            </div>
          </el-tab-pane>
          <el-tab-pane label="{{ __('admin/attribute.attribute_value') }}" name="address" v-if="form.id">
            @hook('admin.attribute.form.tabs.address.before')
            <button class="btn btn-primary mb-3" type="button" @click="editAddress">{{ __('common.add') }}</button>
            <div class="table-push">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>{{ __('admin/attribute.attribute_value') }}</th>
                    @hook('admin.attribute.form.tabs.address.table.headers')
                    <th width="160px">{{ __('common.action') }}</th>
                  </tr>
                </thead>
                <tbody v-if="source.attributeValues.length">
                  <tr v-for="item, index in source.attributeValues" :key="index">
                    <td>@{{ item.id }}</td>
                    <td>@{{ item.description?.name || '' }}</td>
                    @hook('admin.attribute.form.tabs.address.table.body')
                    <td>
                      @hook('admin.attribute.form.tabs.address.table.body.after')
                      <button class="btn btn-outline-secondary btn-sm" type="button" @click="editAddress(index)">{{ __('common.edit') }}</button>
                      <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteAddress(item.id, index)">{{ __('common.delete') }}</button>
                      @hook('admin.attribute.form.tabs.address.table.body.after')
                    </td>
                </tbody>
                <tbody v-else><tr><td colspan="9" class="border-0"><x-admin-no-data /></td></tr></tbody>
              </table>
            </div>
            @hook('admin.attribute.form.tabs.address.after')
          </el-tab-pane>
          @hook('admin.attribute.form.tabs.after')
        </el-tabs>
      </el-form>

      @hook('admin.attribute.form.content.after')
    </div>

    <el-dialog title="{{ __('admin/attribute.attribute_value') }}" :visible.sync="dialog.show" width="670px"
      @close="closeDialog('valuesform')" :close-on-click-modal="false">

      <el-form ref="valuesform" :rules="attributeRules" :model="dialog.form" label-width="155px">
        @hook('admin.attribute.form.dialog.before')
        <el-form-item label="{{ __('common.name') }}" required class="language-inputs">
          <el-form-item  :prop="'name.' + lang.code" :inline-message="true"  v-for="lang, lang_i in source.languages" :key="lang_i"
            :rules="[
              { required: true, message: '{{ __('common.error_required', ['name' => __('common.name')]) }}', trigger: 'blur' },
            ]"
          >
            <el-input size="mini" v-model="dialog.form.name[lang.code]" placeholder="{{ __('common.name') }}"><template slot="prepend">@{{lang.name}}</template></el-input>
          </el-form-item>

          @hook('admin.product.attributes.values.edit.dialog.name.after')

        </el-form-item>
        @hook('admin.attribute.form.dialog.after')
        <el-form-item>
          <div class="d-flex d-lg-block mt-4">
            @hook('admin.attribute.form.dialog.submit.before')
            <el-button type="primary" @click="formSubmit('valuesform')">{{ __('common.save') }}</el-button>
            <el-button @click="closeDialog('valuesform')">{{ __('common.cancel') }}</el-button>
            @hook('admin.attribute.form.dialog.submit.after')
          </div>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
@endsection

@push('footer')
  <script>
    let app = new Vue({
      el: '#customer-app-form',
      data: {
        customerTab: 'customer',
        form: {
          id: @json($attribute['id']),
          name: {},
          attribute_group_id: @json($attribute['attribute_group_id']),
          sort_order: @json($attribute['sort_order']),
        },

        addresses: @json($customer['addresses'] ?? []),

        source: {
          languages: @json(locales()),
          locale: @json(locale()),
          descriptions: @json($attribute['descriptions']),
          attributeValues: @json($attribute['values']),
          attributeGroup: @json($attribute_group ?? []),
        },

        dialog: {
          show: false,
          index: null,
          form: {
            id: '',
            name: {},
            // sort_order: 0,
          }
        },

        rules: {
          name: [{required: true, message: "{{ __('common.error_required', ['name' => __('admin/customer.user_name')] ) }}", trigger: 'blur'}, ],
          sort_order: [{required: true,message: '{{ __('common.error_required', ['name' => __('common.sort_order')])}}',trigger: 'blur'}, ],
        },

        attributeRules: {

        },

        @hook('admin.attribute.form.vue.data')
      },

      mounted() {
        this.source.languages.forEach((item) => {
          this.$set(this.form.name, item.code, this.source.descriptions.find(e => e.locale == item.code)?.name || '')
        });

        @hook('admin.attribute.form.vue.mounted')
      },

      methods: {
        submitForm(form) {
          const self = this;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{__('common.error_form')}}');
              return;
            }

            $http.put(`attributes/{{ $attribute['id'] }}`, self.form).then((res) => {
              layer.msg(res.message);
              location = '{{ admin_route("attributes.index") }}'
            })
          });
        },

        editAddress(index) {
          if (typeof index == 'number') {
            this.dialog.index = index;
            let descriptions = this.source.attributeValues[index].descriptions;
            this.source.languages.forEach((item) => {
              this.$set(this.dialog.form.name, item.code, descriptions.find(e => e.locale == item.code)?.name || '')
            })
            this.dialog.form.id = this.source.attributeValues[index].id
          }

          this.dialog.show = true
        },

        deleteAddress(id, index) {
          this.$confirm('{{ __('admin/customer.confirm_delete_address') }}', '{{__('common.text_hint')}}', {
            confirmButtonText: '{{__('common.confirm')}}',
            cancelButtonText: '{{__('common.cancel')}}',
            type: 'warning'
          }).then(() => {
            $http.delete(`attributes/{{ $attribute['id'] }}/values/${id}`).then((res) => {
              this.$message.success(res.message);
              this.source.attributeValues.splice(index, 1)
            })
          }).catch(()=>{})
        },

        formSubmit(form) {
          const self = this;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{ __('common.error_form') }}');
              return;
            }

            const type = this.dialog.form.id ? 'put' : 'post';
            const url = type == 'post' ? `attributes/${this.form.id}/values` : `attributes/${this.form.id}/values/${this.dialog.form.id}`;

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.form.id) {
                this.source.attributeValues[this.dialog.index] = res.data
              } else {
                this.source.attributeValues.push(res.data)
              }

              this.dialog.show = false
            })
          });
        },

        closeDialog(form) {
          this.$refs[form].resetFields();
          this.dialog.form.id = 0
          this.dialog.form.name = {}
          this.dialog.show = false
        },

        @hook('admin.attribute.form.vue.methods')
      },

      @hook('admin.attribute.form.vue.options')
    });
  </script>
@endpush
