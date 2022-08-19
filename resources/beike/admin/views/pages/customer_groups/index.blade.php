@extends('admin::layouts.master')

@section('title', '用户组')

@section('content')
  <div id="customer-app" class="card" v-cloak>
    <div class="card-body">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCustomersCreate('add', null)">创建用户组</button>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>名称</th>
            <th>描述</th>
            <th>等级</th>
            <th>创建时间</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="group, index in customer_groups" :key="index">
            <td>@{{ group.id }}</td>
            <td>@{{ group.description?.name || '' }}</td>
            <td>@{{ group.description?.description || '' }}</td>
            <td>@{{ group.level }}</td>
            <td>@{{ group.created_at }}</td>
            <td>
              <button class="btn btn-outline-secondary btn-sm" @click="checkedCustomersCreate('edit', index)">编辑</button>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(group.id, index)" v-if="customer_groups.length > 1">删除</button>
            </td>
          </tr>
        </tbody>
      </table>

      {{-- {{ $customer_groups->links('admin::vendor/pagination/bootstrap-4') }} --}}
    </div>

    <el-dialog title="创建用户组" :visible.sync="dialog.show" width="600px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        <el-form-item label="名称" required class="language-inputs">
          <el-form-item  :prop="'name.' + lang.code" :inline-message="true"  v-for="lang, lang_i in source.languages" :key="lang_i"
            :rules="[
              { required: true, message: '请输入名称', trigger: 'blur' },
            ]"
          >
            <el-input size="mini" v-model="dialog.form.name[lang.code]" placeholder="用户名"><template slot="prepend">@{{lang.name}}</template></el-input>
          </el-form-item>
        </el-form-item>

        <el-form-item label="描述" class="language-inputs">
          <el-form-item v-for="lang, lang_i in source.languages" :key="lang_i">
            <el-input size="mini" v-model="dialog.form.description[lang.code]" placeholder="描述"><template slot="prepend">@{{lang.name}}</template></el-input>
          </el-form-item>
        </el-form-item>

        <el-form-item label="等级">
          <el-input class="mb-0" v-model="dialog.form.level" placeholder="等级"></el-input>
        </el-form-item>

        <el-form-item label="消费额度">
          <el-input class="mb-0" type="number" v-model="dialog.form.total" placeholder="消费额度"></el-input>
        </el-form-item>

        <el-form-item label="折扣率">
          <el-input class="mb-0" type="number" v-model="dialog.form.discount_factor" placeholder="折扣率"></el-input>
        </el-form-item>

        <el-form-item label="奖励积分系数">
          <el-input class="mb-0" type="number" v-model="dialog.form.reward_point_factor" placeholder="奖励积分系数"></el-input>
        </el-form-item>

        <el-form-item label="使用积分系数">
          <el-input class="mb-0" type="number" v-model="dialog.form.use_point_factor" placeholder="使用积分系数"></el-input>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="addCustomersFormSubmit('form')">保存</el-button>
          <el-button @click="closeCustomersDialog('form')">取消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#customer-app',

      data: {
        customer_groups: @json($customer_groups ?? []),

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
            name: {},
            description: {},
            total: '', //消费额度
            discount_factor: '', // 折扣率
            reward_point_factor: '', // 奖励积分系数使用积分系数
            use_point_factor: '', // 使用积分系数
          },
        },

        rules: {
          // password: [{required: true,message: '请输入密码',trigger: 'blur'}, ],
        }
      },

      beforeMount() {
        // this.source.languages.forEach(e => {
        //   this.$set(this.dialog.form.name, e.code, '')
        //   this.$set(this.dialog.form.description, e.code, '')
        // })
      },

      methods: {
        checkedCustomersCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index

          if (type == 'edit') {
            let group = this.customer_groups[index];

            this.dialog.form = {
              id: group.id,
              name: {},
              description: {},
              total: group.total, //消费额度
              level: group.level, //消费额度
              discount_factor: group.discount_factor, // 折扣率
              reward_point_factor: group.reward_point_factor, // 奖励积分系数使用积分系数
              use_point_factor: group.use_point_factor, // 使用积分系数
              status: 1,
            }

            group.descriptions.forEach((e, index) => {
              this.$set(this.dialog.form.name, e.locale, e.name)
              this.$set(this.dialog.form.description, e.locale, e.description)
              // this.dialog.form.name[e.locale] = e.name
              // this.dialog.form.description[e.locale] = e.description
            })
          }
        },

        addCustomersFormSubmit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'customer_groups' : 'customer_groups/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.type == 'add') {
                this.customer_groups.push(res.data)
              } else {
                this.customer_groups[this.dialog.index] = res.data
              }
              this.dialog.show = false
            })
          });
        },

        deleteCustomer(id, index) {
          const self = this;
          this.$confirm('确定要删除用户组吗？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            $http.delete('customer_groups/' + id).then((res) => {
              this.$message.success(res.message);
              self.customer_groups.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          this.$refs[form].resetFields();
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.form.name = {};
          this.dialog.form.description = {};
          this.dialog.show = false
        }
      }
    })
  </script>
@endpush
