@extends('admin::layouts.master')

@section('title', __('admin/admin_roles.role_management'))

@section('content')
  <div id="app" class="card" v-cloak>
    <div class="card-body h-min-600">
      <el-form ref="form" :rules="rules" :model="form" label-width="100px">
        <el-form-item label="{{ __('admin/admin_roles.role_name') }}" prop="name">
          <el-input v-model="form.name" placeholder="{{ __('admin/admin_roles.role_name') }}" class="w-auto"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('admin/admin_roles.permission') }}" prop="roles">
          <div class="roles-wrap border w-max-900">
            <div class="bg-dark p-2 text-dark bg-opacity-10 px-2">
              <el-button size="small" @click="updateAllState('core_permissions', true)">@lang('admin/admin_roles.select_all')</el-button>
              <el-button size="small" @click="updateAllState('core_permissions', false)">@lang('admin/admin_roles.unselect_all')</el-button>
            </div>
            <div v-for="role, index in form.core_permissions" :key="index">
              <div class="bg-light px-2 d-flex">
                @{{ role.title }}
                <div class="row-update ms-2 link-secondary">[<span @click="updateState('core_permissions', true, index)">{{ __('common.select_all') }}</span> / <span @click="updateState('core_permissions', false, index)">{{ __('common.cancel') }}</span>]</div>
              </div>
              <div class="role-methods">
                <div class="d-flex flex-wrap px-3">
                  <div v-for="method,index in role.permissions" class="me-3">
                    <el-checkbox class="text-dark" v-model="method.selected">@{{ method.name }}</el-checkbox>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </el-form-item>

        <el-form-item label="{{ __('admin/admin_roles.plugin_permission') }}" prop="roles" v-if="form.plugin_permissions.length">
          <div class="roles-wrap border w-max-900">
            <div class="bg-dark p-2 text-dark bg-opacity-10 px-2">
              <el-button size="small" @click="updateAllState('plugin_permissions', true)">@lang('admin/admin_roles.select_all')</el-button>
              <el-button size="small" @click="updateAllState('plugin_permissions', false)">@lang('admin/admin_roles.unselect_all')</el-button>
            </div>
            <div v-for="role, index in form.plugin_permissions" :key="index">
              <div class="bg-light px-2 d-flex">
                @{{ role.title }}
                <div class="row-update ms-2 link-secondary">[<span @click="updateState('plugin_permissions', true, index)">{{ __('common.select_all') }}</span> / <span @click="updateState('plugin_permissions', false, index)">{{ __('common.cancel') }}</span>]</div>
              </div>
              <div class="role-methods">
                <div class="d-flex flex-wrap px-3">
                  <div v-for="method,index in role.permissions" class="me-3">
                    <el-checkbox class="text-dark" v-model="method.selected">@{{ method.name }}</el-checkbox>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </el-form-item>

        <el-form-item class="mt-5">
          <el-button type="primary" @click="addFormSubmit('form')">{{ __('common.save') }}</el-button>
          <el-button @click="closeCustomersDialog('form')">{{ __('common.cancel') }}</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#app',

      data: {
        form: {
          id: @json($role->id ?? null),
          name: @json($role->name ?? ''),
          core_permissions: @json($core_permissions ?? []),
          plugin_permissions: @json($plugin_permissions ?? []),
        },

        source: {

        },

        rules: {
          name: [{required: true,message: '{{ __('common.error_required', ['name' => __('admin/admin_roles.role_name')]) }}',trigger: 'blur'}, ],
        }
      },

      beforeMount() {
        // this.source.languages.forEach(e => {
        //   this.$set(this.form.name, e.code, '')
        //   this.$set(this.form.description, e.code, '')
        // })
      },

      methods: {
        updateState(key, type, index) {
          this.form[key][index].permissions.map(e => e.selected = !!type)
        },

        updateAllState(key, type) {
          this.form[key].forEach(e => {
            e.permissions.forEach(method => {
              method.selected = !!type
            });
          });
        },

        addFormSubmit(form) {
          const self = this;
          const type = this.form.id == null ? 'post' : 'put';
          const url = this.form.id == null ? 'admin_roles' : 'admin_roles/' + this.form.id;

          this.$refs[form].validate((valid) => {
            // this.form.permissions.forEach(e => {
            //   e.permissions = e.permissions.filter(x => x.selected).map(j => j.code)
            // });

            if (!valid) {
              this.$message.error('{{ __('common.error_form') }}');
              return;
            }

            $http[type](url, this.form).then((res) => {
              layer.msg(res.message);
              location = '{{ admin_route('admin_roles.index') }}'
            })
          });
        },
      }
    })
  </script>

  <style>
    .roles-wrap .el-checkbox.text-dark .el-checkbox__label {
      font-size: 12px;
      padding-left: 6px;
    }

    .row-update {
      cursor: pointer;
      font-size: 12px;
    }
  </style>
@endpush
