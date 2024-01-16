@extends('admin::layouts.master')

@section('title', __('admin/common.rma_reasons_index'))

@section('content')
  <div id="tax-classes-app" class="card" v-cloak>
    <div class="card-body h-min-600">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCreate('add', null)">{{ __('common.add') }}</button>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>{{ __('common.name') }}</th>
            <th class="text-end">{{ __('common.action') }}</th>
          </tr>
        </thead>
        <tbody v-if="rmaReasons.length">
          <tr v-for="language, index in rmaReasons" :key="index">
            <td>@{{ language.id }}</td>
            <td><span class="text-hidden">@{{ language.name }}</span></td>
            <td class="text-end">
              <button class="btn btn-outline-secondary btn-sm mb-1 mb-sm-0" @click="checkedCreate('edit', index)">{{ __('common.edit') }}</button>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(language.id, index)">{{ __('common.delete') }}</button>
            </td>
          </tr>
        </tbody>
        <tbody v-else><tr><td colspan="3" class="border-0"><x-admin-no-data /></td></tr></tbody>
      </table>
    </div>

    <el-dialog title="{{ __('admin/common.rma_reasons_index') }}" :visible.sync="dialog.show" width="500px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        <el-form-item label="{{ __('common.name') }}" required class="language-inputs">
          <el-form-item  :prop="'name.' + lang.code" :inline-message="true"  v-for="lang, lang_i in source.languages" :key="lang_i"
            :rules="[
              { required: true, message: '{{ __('common.error_input_required') }}', trigger: 'blur' },
            ]"
          >
            <el-input size="mini" v-model="dialog.form.name[lang.code]" placeholder="{{ __('common.name') }}"><template slot="prepend">@{{lang.name}}</template></el-input>
          </el-form-item>
        </el-form-item>

        <el-form-item class="mt-5">
          <el-button type="primary" @click="addFormSubmit('form')">{{ __('common.save') }}</el-button>
          <el-button @click="closeCustomersDialog('form')">{{ __('common.cancel') }}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
@endsection

@push('footer')
  @include('admin::shared.vue-image')

  <script>
    new Vue({
      el: '#tax-classes-app',

      data: {
        rmaReasons: @json($rmaReasons ?? []),

        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            id: null,
            name: {},
          },
        },

        rules: {
          name: [{required: true,message: '{{ __('common.error_required', ['name' => __('common.name')]) }}',trigger: 'blur'}, ],
        },

        source: {
          languages: @json($languages ?? []),
        },
      },

      methods: {
        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index

          if (type == 'edit') {
            let tax = JSON.parse(JSON.stringify(this.rmaReasons[index]));

            this.dialog.form = {
              id: tax.id,
              name: tax.names,
            }
          }
        },

        loadData() {
          $http.get(`rma_reasons?page=${this.page}`).then((res) => {
            this.rmaReasons = res.data.rmaReasons;
          })
        },

        statusChange(e, index) {
          const id = this.rmaReasons[index].id;

          // $http.put(`rmaReasons/${id}`).then((res) => {
          //   layer.msg(res.message);
          // })
        },

        addFormSubmit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'rma_reasons' : 'rma_reasons/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{ __('common.error_form') }}');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              this.loadData();

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
            $http.delete('rma_reasons/' + id).then((res) => {
              this.$message.success(res.message);
              self.rmaReasons.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          this.$refs[form].resetFields();
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.form.name = {};
          this.dialog.show = false
        }
      }
    })
  </script>
@endpush

