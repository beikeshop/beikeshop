@extends('admin::layouts.master')

@section('title', '角色管理')

@section('content')
  <div id="app" class="card" v-cloak>
    <div class="card-body h-min-600">
      <el-form ref="form" :rules="rules" :model="form" label-width="100px" class="w-max-700">
        <el-form-item label="角色名称" prop="title">
          <el-input v-model="form.title" placeholder="角色名称"></el-input>
        </el-form-item>

        <el-form-item label="描述" prop="description">
          <el-input v-model="form.description" placeholder="描述"></el-input>
        </el-form-item>

        <el-form-item label="权限" prop="roles">
          <div class="roles-wrap border">
            <div class="header-wrap px-2">
              <button class="btn btn-outline-dark btn-sm me-3" type="button">选中所有</button>
              <button class="btn btn-outline-dark btn-sm" type="button">取消选中</button>
            </div>
            <div v-for="role, index in form.roles" :key="index">
              <div class="bg-light px-2 d-flex">
                @{{ role.title }}
                <div class="row-update ms-2">[<span @click="updateState(true, index)" class="link-secondary">全选</span>/<span @click="updateState(false, index)" class="link-secondary">取消</span>]</div>
              </div>
              <div class="role.methods">
                <div class="d-flex px-3">
                  <div v-for="method,index in role.methods" class="me-3">
                    <el-checkbox class="text-dark" v-model="method.selected">@{{ method.name }}</el-checkbox>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </el-form-item>

        <el-form-item class="mt-5">
          <el-button type="primary" @click="addFormSubmit('form')">保存</el-button>
          <el-button @click="closeCustomersDialog('form')">取消</el-button>
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
          name: '',
          roles: [
            {
              title: '商品权限',
              methods: [
                {name:'列表', code: 'list', selected: false},
                {name:'创建', code: 'create', selected: false},
                {name:'查看', code: 'show', selected: false},
                {name:'编辑', code: 'update', selected: false},
                {name:'删除', code: 'destroy', selected: false},
              ]
            },
            {
              title: '订单权限',
              methods: [
                {name:'列表', code: 'list', selected: false},
                {name:'创建', code: 'create', selected: false},
                {name:'查看', code: 'show', selected: false},
                {name:'编辑', code: 'update', selected: false},
                {name:'删除', code: 'destroy', selected: false},
              ]
            },
          ]
        },

        source: {

        },

        rules: {
          title: [{required: true,message: '请输入角色名称',trigger: 'blur'}, ],
          description: [{required: true,message: '请输入描述',trigger: 'blur'}, ],
        }
      },

      beforeMount() {
        // this.source.languages.forEach(e => {
        //   this.$set(this.form.name, e.code, '')
        //   this.$set(this.form.description, e.code, '')
        // })
      },

      methods: {
        updateState(type, index) {
          this.form.roles[index].methods.map(e => e.selected = !!type)
        },

        addFormSubmit(form) {
          const self = this;
          const type = this.type == 'add' ? 'post' : 'put';
          const url = this.type == 'add' ? 'roles' : 'roles/' + this.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            // $http[type](url, this.form).then((res) => {
            //   this.$message.success(res.message);
            //   if (this.type == 'add') {
            //     this.roles.push(res.data)
            //   } else {
            //     this.roles[this.index] = res.data
            //   }

            //   this.show = false
            // })
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
    }
  </style>
@endpush
