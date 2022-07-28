@extends('admin::layouts.master')

@section('title', '品牌管理')

@section('content')
  <div id="customer-app" class="card" v-cloak>
    <div class="card-body">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCreate('add', null)">创建品牌</button>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>名称</th>
            <th>图标</th>
            <th>排序</th>
            <th>状态</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="brand, index in brands" :key="index">
            <td>@{{ brand.id }}</td>
            <td>@{{ brand.name }}</td>
            <td>@{{ brand.logo }}</td>
            <td>@{{ brand.sort_order }}</td>
            <td>@{{ brand.status }}</td>
            <td>
              <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">编辑</button>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteItem(brand.id, index)">删除</button>
            </td>
          </tr>
        </tbody>
      </table>

      {{-- {{ $brands->links('admin::vendor/pagination/bootstrap-4') }} --}}
    </div>

    <el-dialog title="创建用户组" :visible.sync="dialog.show" width="600px"
      @close="closeDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        <el-form-item label="名称" prop="name">
          <el-input class="mb-0" v-model="dialog.form.name" placeholder="名称"></el-input>
        </el-form-item>

        <el-form-item label="图标">
          <vue-image v-model="dialog.form.logo"></vue-image>
        </el-form-item>

        <el-form-item label="排序">
          <el-input class="mb-0" type="number" v-model="dialog.form.sort_order" placeholder="排序"></el-input>
        </el-form-item>

        <el-form-item label="状态">
          <el-switch v-model="dialog.form.status" :active-value="1" :inactive-value="0"></el-switch>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="submit('form')">保存</el-button>
          <el-button @click="closeDialog('form')">取消</el-button>
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
        brands: @json($brands->data ?? []),

        source: {
          // languages: ['zh-ck','en-gb']
          languages: @json($languages ?? []),
        },

        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            id: null,
            name: '',
            logo: '',
            sort_order: '',
            status: '',
          },
        },

        rules: {
          // password: [{required: true,message: '请输入密码',trigger: 'blur'}, ],
        }
      },

      methods: {
        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index

          if (type == 'edit') {
            let brand = this.brands[index];

            this.dialog.form = {
              id: brand.id,
              name: brand.name,
              logo: brand.logo,
              sort_order: brand.sort_order,
              status: brand.status,
            }
          }
        },

        submit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'brands' : 'brands/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (type == 'add') {
                this.brands.push(res.data)
              } else {
                this.brands[this.dialog.index] = res.data
              }
              this.dialog.show = false
            })
          });
        },

        deleteItem(id, index) {
          const self = this;
          this.$confirm('确定要删除用户码？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            $http.delete('brands/' + id).then((res) => {
              this.$message.success(res.message);
              self.brands.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeDialog(form) {
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.show = false
        }
      }
    })
  </script>
@endpush
