@extends('admin::layouts.master')

@section('title', '国家管理')

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
            <th>名称</th>
            <th>创建时间</th>
            <th>修改时间</th>
            <th>排序</th>
            <th>状态</th>
            <th class="text-end">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="country, index in country.data" :key="index">
            <td>@{{ country.id }}</td>
            <td>@{{ country.name }}</td>
            <td>@{{ country.created_at }}</td>
            <td>@{{ country.updated_at }}</td>
            <td>@{{ country.sort_order }}</td>
            <td>
              <span v-if="country.status" class="text-success">{{ __('common.enable') }}</span>
              <span v-else class="text-secondary">{{ __('common.disable') }}</span>
            </td>
            <td class="text-end">
              <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">编辑</button>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(country.id, index)">删除</button>
            </td>
          </tr>
        </tbody>
      </table>

      <el-pagination layout="prev, pager, next" background :page-size="country.per_page" :current-page.sync="page"
        :total="country.total"></el-pagination>
    </div>

    <el-dialog title="国家管理" :visible.sync="dialog.show" width="500px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        <el-form-item label="国家名称" prop="name">
          <el-input v-model="dialog.form.name" placeholder="名称"></el-input>
        </el-form-item>

        <el-form-item label="排序">
          <el-input v-model="dialog.form.sort_order" placeholder="排序"></el-input>
        </el-form-item>

        <el-form-item label=" 状态">
          <el-switch v-model="dialog.form.status" :active-value="1" :inactive-value="0"></el-switch>
        </el-form-item>

        <el-form-item class="mt-5">
          <el-button type="primary" @click="addFormSubmit('form')">保存</el-button>
          <el-button @click="closeCustomersDialog('form')">取消</el-button>
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
        country: @json($country ?? []),
        page: 1,

        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            id: null,
            name: '',
            sort_order: '',
            status: 1,
          },
        },

        rules: {
          name: [{required: true,message: '请输入国家名称',trigger: 'blur'}, ],
        }
      },

      watch: {
        page: function() {
          this.loadData();
        },
      },

      methods: {
        loadData() {
          // $http.get(`brands?page=${this.page}`).then((res) => {
          //   this.brands = res.data.brands;
          // })
        },

        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index

          if (type == 'edit') {
            this.dialog.form = JSON.parse(JSON.stringify(this.country.data[index]));
          }
        },

        statusChange(e, index) {
          const id = this.country.data[index].id;

          // $http.put(`languages/${id}`).then((res) => {
          //   layer.msg(res.message);
          // })
        },

        addFormSubmit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'countries' : 'countries/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.type == 'add') {
                this.languages.push(res.data)
              } else {
                this.languages[this.dialog.index] = res.data
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
            $http.delete('languages/' + id).then((res) => {
              this.$message.success(res.message);
              self.languages.splice(index, 1)
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
