@extends('admin::layouts.master')

@section('title', '品牌管理')

@section('content')
  <div id="customer-app" class="card h-min-600" v-cloak>
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
            <th>首字母</th>
            <th>状态</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="brand, index in brands.data" :key="index">
            <td>@{{ brand.id }}</td>
            <td>@{{ brand.name }}</td>
            <td><div class="wh-50"><img :src="thumbnail(brand.logo)" class="img-fluid"></div></td>
            <td>@{{ brand.sort_order }}</td>
            <td>@{{ brand.first }}</td>
            <td>@{{ brand.status }}</td>
            <td>
              <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">编辑</button>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteItem(brand.id, index)">删除</button>
            </td>
          </tr>
        </tbody>
      </table>

      <el-pagination layout="prev, pager, next" background :page-size="brands.per_page" :current-page.sync="page"
        :total="brands.total"></el-pagination>
    </div>

    <el-dialog title="品牌" :visible.sync="dialog.show" width="600px"
      @close="closeDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        <el-form-item label="名称" prop="name">
          <el-input class="mb-0" v-model="dialog.form.name" placeholder="名称"></el-input>
        </el-form-item>

        <el-form-item label="图标" prop="logo" required>
          <vue-image v-model="dialog.form.logo"></vue-image>
        </el-form-item>

        <el-form-item label="首字母" prop="first">
          <el-input class="mb-0" v-model="dialog.form.first" placeholder="首字母"></el-input>
        </el-form-item>

        <el-form-item label="排序">
          <el-input class="mb-0" type="number" v-model="dialog.form.sort_order" placeholder="排序"></el-input>
        </el-form-item>

        <el-form-item label="状态">
          <el-switch v-model="dialog.form.status" :active-value="1" :inactive-value="0"></el-switch>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="submit('form')">保存</el-button>
          <el-button @click="dialog.show = false">取消</el-button>
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
        page: 1,
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
            first: '',
            sort_order: '',
            status: 1,
          },
        },

        rules: {
          name: [{required: true,message: '请输入名称',trigger: 'blur'}, ],
          first: [{required: true,message: '请输入首字母',trigger: 'blur'}, ],
          logo: [{required: true,message: '请上传图标',trigger: 'change'}, ],
        }
      },

      watch: {
        page: function() {
          this.loadData();
        },
      },

      methods: {
        loadData() {
          $http.get(`brands?page=${this.page}`).then((res) => {
            this.brands = res.data.brands;
          })
        },

        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index

          if (type == 'edit') {
            this.dialog.form = JSON.parse(JSON.stringify(this.brands.data[index]))
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

              if (this.dialog.type == 'add') {
                this.brands.data.unshift(res.data)
              } else {
                this.brands.data[this.dialog.index] = res.data
              }
              this.dialog.show = false
            })
          });
        },

        deleteItem(id, index) {
          const self = this;
          this.$confirm('确定要删除品牌码？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
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
