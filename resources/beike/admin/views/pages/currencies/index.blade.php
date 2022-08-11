@extends('admin::layouts.master')

@section('title', '货币管理')

@section('content')
  <div id="tax-classes-app" class="card" v-cloak>
    <div class="card-body h-min-600">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCreate('add', null)">添加</button>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>名称</th>
            <th>编码</th>
            <th>左符号</th>
            <th>右符号</th>
            <th>小数位数</th>
            <th>汇率值</th>
            <th>状态</th>
            <th class="text-end">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="language, index in currencies" :key="index">
            <td>@{{ language.id }}</td>
            <td>@{{ language.name }}</td>
            <td>@{{ language.code }}</td>
            <td>@{{ language.symbol_left }}</td>
            <td>@{{ language.symbol_right }}</td>
            <td>@{{ language.decimal_place }}</td>
            <td>@{{ language.value }}</td>
            <td>@{{ language.status }}</td>
            <td class="text-end">
              <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">编辑</button>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(language.id, index)">删除</button>
            </td>
          </tr>
        </tbody>
      </table>

      {{-- {{ $currencies->links('admin::vendor/pagination/bootstrap-4') }} --}}
    </div>

    <el-dialog title="货币" :visible.sync="dialog.show" width="500px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        <el-form-item label="code" prop="name">
          <el-input v-model="dialog.form.name" placeholder="code"></el-input>
        </el-form-item>

        <el-form-item label="编码" prop="code">
          <el-input v-model="dialog.form.code" placeholder="编码"></el-input>
        </el-form-item>

        <el-form-item label="左符号">
          <el-input v-model="dialog.form.symbol_left" placeholder="左符号"></el-input>
        </el-form-item>

        <el-form-item label="右符号">
          <el-input v-model="dialog.form.symbol_right" placeholder="左符号"></el-input>
        </el-form-item>

        <el-form-item label="小数位数">
          <el-input v-model="dialog.form.decimal_place" placeholder="左符号"></el-input>
        </el-form-item>

        <el-form-item label="汇率值">
          <el-input v-model="dialog.form.value" placeholder="左符号"></el-input>
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
        currencies: @json($currencies ?? []),

        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            id: null,
            name: '',
            code: '',
            symbol_left: '',
            symbol_right: '',
            decimal_place: '',
            value: '',
            status: 1,
          },
        },

        rules: {
          name: [{required: true,message: '请输入名称',trigger: 'blur'}, ],
          code: [{required: true,message: '请输入编码',trigger: 'blur'}, ],
        }
      },

      methods: {
        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index

          if (type == 'edit') {
            this.dialog.form = this.currencies[index]
          }
        },

        addFormSubmit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'currencies' : 'currencies/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.type == 'add') {
                this.currencies.push(res.data)
              } else {
                this.currencies[this.dialog.index] = res.data
              }

              this.dialog.show = false
            })
          });
        },

        deleteCustomer(id, index) {
          const self = this;
          this.$confirm('确定要删除语言码？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            $http.delete('currencies/' + id).then((res) => {
              this.$message.success(res.message);
              self.currencies.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.show = false
          this.$refs[form].resetFields();
        }
      }
    })
  </script>
@endpush
