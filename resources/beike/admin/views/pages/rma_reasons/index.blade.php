{{-- @extends('admin::layouts.master')

@section('title', '退换货原因')

@section('content')
  <div id="customer-app" class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCreate('add', null)">创建</button>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>名称</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($rmaReasons as $rmaReason)
            <tr>
              <td>{{ $rmaReason->id }}</td>
              <td>{{ $rmaReason->name }}</td>
              <td><a href="{{ admin_route('rma_reasons.show', [$rmaReason->id]) }}"
                  class="btn btn-outline-secondary btn-sm">查看</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

@push('footer')
  <script></script>
@endpush --}}


@extends('admin::layouts.master')

@section('title', '退换货原因')

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
        <tbody>
          <tr v-for="language, index in rmaReasons" :key="index">
            <td>@{{ language.id }}</td>
            <td>@{{ language.name }}</td>
            <td>
              <span v-if="language.status" class="text-success">{{ __('common.enable') }}</span>
              <span v-else class="text-secondary">{{ __('common.disable') }}</span>
            </td>
            <td class="text-end">
              <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">{{ __('common.edit') }}</button>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(language.id, index)">{{ __('common.delete') }}</button>
            </td>
          </tr>
        </tbody>
      </table>

      {{-- {{ $languages->links('admin::vendor/pagination/bootstrap-4') }} --}}
    </div>

    <el-dialog title="退换货原因" :visible.sync="dialog.show" width="500px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        {{-- <el-form-item label="{{ __('common.name') }}" prop="name">
          <el-input v-model="dialog.form.name" placeholder="{{ __('common.name') }}"></el-input>
        </el-form-item> --}}

        <el-form-item label="{{ __('common.name') }}" required class="language-inputs">
          <el-form-item  :prop="'name.' + lang.code" :inline-message="true"  v-for="lang, lang_i in source.languages" :key="lang_i"
            :rules="[
              { required: true, message: '{{ __('common.error_input_required') }}', trigger: 'blur' },
            ]"
          >
            <el-input size="mini" v-model="dialog.form.name[lang.code]" placeholder="请填写名称"><template slot="prepend">@{{lang.name}}</template></el-input>
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
            let tax = this.rmaReasons[index];

            this.dialog.form = {
              id: tax.id,
              name: tax.name,
            }
          }
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
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.type == 'add') {
                this.rmaReasons.push(res.data)
              } else {
                this.rmaReasons[this.dialog.index] = res.data
              }

              this.dialog.show = false
            })
          });
        },

        deleteCustomer(id, index) {
          const self = this;
          this.$confirm('确定要删除语言吗？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            $http.delete('rmaReasons/' + id).then((res) => {
              this.$message.success(res.message);
              self.rmaReasons.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          this.$refs[form].resetFields();
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.show = false
        }
      }
    })
  </script>
@endpush

