@extends('admin::layouts.master')

@section('title', __('admin/attribute.index'))

@section('content')
  <div id="app" class="card" v-cloak>
    <div class="card-body">
      @hook('admin.attribute.index.content.before')

      <div class="d-flex justify-content-between mb-4">
        @hook('admin.attribute.index.content.top_buttons.before')
        <button type="button" class="btn btn-primary" @click="checkedCreate">{{ __('admin/attribute.create_at') }}</button>
        @hook('admin.attribute.index.content.top_buttons.after')
      </div>
      <div class="table-push h-min-500">
        <table class="table">
          <thead>
            <tr>
              <th>{{ __('common.id') }}</th>
              <th>{{ __('common.name') }}</th>
              <th>{{ __('admin/attribute_group.index') }}</th>
              <th>{{ __('common.sort_order') }}</th>
              <th>{{ __('common.created_at') }}</th>
              @hook('admin.attribute.index.table.headers')
              <th width="150px">{{ __('common.action') }}</th>
            </tr>
          </thead>
          <tbody v-if="attributes.data.length">
            <tr v-for="item, index in attributes.data" :key="index">
              <td>@{{ item.id }}</td>
              <td>@{{ item.name }}</td>
              <td>@{{ item.attribute_group_name }}</td>
              <td>@{{ item.sort_order }}</td>
              <td>@{{ item.created_at }}</td>
              @hook('admin.attribute.index.table.body')
              <td>
                @hook('admin.attribute.index.table.body.actions.before')
                <a class="btn btn-outline-secondary btn-sm" :href="linkEdit(item.id)">{{ __('common.edit') }}</a>
                <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteItem(item.id)">{{ __('common.delete') }}</button>
                @hook('admin.attribute.index.table.body.actions.after')
              </td>
            </tr>
          </tbody>
          <tbody v-else><tr><td colspan="9" class="border-0"><x-admin-no-data /></td></tr></tbody>
        </table>
      </div>

      <el-pagination v-if="attributes.data.length" layout="prev, pager, next" background :page-size="attributes.per_page" :current-page.sync="page"
        :total="attributes.total"></el-pagination>

      @hook('admin.attribute.index.content.after')
    </div>

    <el-dialog title="{{ __('admin/attribute.index') }}" :visible.sync="dialog.show" width="670px"
      @close="closeDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="155px">
        @hook('admin.attribute.index.dialog.before')

        <el-form-item label="{{ __('common.name') }}" required class="language-inputs">
          <el-form-item  :prop="'name.' + lang.code" :inline-message="true"  v-for="lang, lang_i in source.languages" :key="lang_i"
            :rules="[
              { required: true, message: '{{ __('common.error_required', ['name' => __('common.name')]) }}', trigger: 'blur' },
            ]"
          >
            <el-input size="mini" v-model="dialog.form.name[lang.code]" placeholder="{{ __('common.name') }}"><template slot="prepend">@{{lang.name}}</template></el-input>
          </el-form-item>

          @hook('admin.product.attributes.add.dialog.name.after')

        </el-form-item>

        <el-form-item label="{{ __('admin/attribute_group.index') }}" required prop="attribute_group_id">
          <el-select v-model="dialog.form.attribute_group_id" placeholder="{{ __('common.please_choose') }}">
            <el-option
              v-for="item in source.attribute_group"
              :key="item.id"
              :label="item.description?.name || ''"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="{{ __('common.sort_order') }}" prop="sort_order">
          <el-input class="mb-0 wp-100" v-model="dialog.form.sort_order" type="number" placeholder="{{ __('common.sort_order') }}"></el-input>
        </el-form-item>

        @hook('admin.attribute.index.dialog.after')

        <el-form-item>
          <div class="d-flex d-lg-block mt-4">
            @hook('admin.attribute.index.dialog.submit.before')
            <el-button type="primary" @click="formSubmit('form')">{{ __('common.save') }}</el-button>
            <el-button @click="closeDialog('form')">{{ __('common.cancel') }}</el-button>
            @hook('admin.attribute.index.dialog.submit.after')
          </div>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
@endsection

@push('footer')
  <script>
    @hook('admin.attribute.index.script.data')

   let app = new Vue({
      el: '#app',

      data: {
        page: 1,
        attributes: @json($attribute_list ?? []),

        source: {
          attribute_group: @json($attribute_group ?? []),
          languages: @json(locales()),
          locale: @json(locale()),
        },

        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            id: null,
            name: {},
            sort_order: 0,
            attribute_group_id: '',
          },
        },

        rules: {
          attribute_group_id: [
            {required: true, message: '{{ __('common.error_required', ['name' => __('admin/attribute_group.index')] ) }}', trigger: 'blur'},
          ],
          sort_order: [{required: true,message: '{{ __('common.error_required', ['name' => __('common.sort_order')])}}',trigger: 'blur'}, ],
        },

        @hook('admin.attribute.index.vue.data')
      },

      watch: {
        page: function() {
          this.loadData();
        },

        @hook('admin.attribute.index.vue.watch')
      },

      methods: {
        loadData() {
          $http.get(`attributes?page=${this.page}`).then((res) => {
            this.attributes = res.data.attribute_list;
          })
        },

        linkEdit(id) {
          return '{{ admin_route('attributes.index') }}' + `/${id}`
        },

        checkedCreate() {
          this.dialog.show = true
        },

        formSubmit(form) {
          const self = this;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{ __('common.error_form') }}');
              return;
            }

            $http.post('attributes', this.dialog.form).then((res) => {
              this.loadData();
              setTimeout(() => this.dialog.show = false, 100)
              this.$confirm('{{ __('admin/attribute.to_info_values') }}', '{{ __('common.created_success') }}', {
                distinguishCancelAndClose: true,
                confirmButtonText: '{{ __('admin/attribute.btn_at') }}',
                cancelButtonText: '{{ __('admin/attribute.btn_later') }}',
              }).then(() => {
                location = this.linkEdit(res.data.id);
              }).catch(()=>{})
            })
          });
        },

        deleteItem(id) {
          const self = this;
          this.$confirm('{{ __('common.confirm_delete') }}', '{{ __('common.text_hint') }}', {
            confirmButtonText: '{{ __('common.confirm') }}',
            cancelButtonText: '{{ __('common.cancel') }}',
            type: 'warning'
          }).then(() => {
            $http.delete(`attributes/${id}`).then((res) => {
              if (res.status == 'fail') {
                layer.msg(res.message,()=>{})
                return;
              }

              layer.msg(res.message)
              window.location.reload();
            })
          }).catch(()=>{})
        },

        closeDialog(form) {
          this.$refs[form].resetFields();
          this.dialog.show = false
        },

        @hook('admin.attribute.index.vue.methods')
      },

      @hook('admin.attribute.index.vue.options')
    })

    @hook('admin.attribute.index.script.after')
  </script>
@endpush
