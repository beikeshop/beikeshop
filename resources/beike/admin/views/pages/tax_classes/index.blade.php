@extends('admin::layouts.master')

@section('title', '税类设置')

@section('content')
  <ul class="nav-bordered nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link active" aria-current="page" href="{{ admin_route('tax_classes.index') }}">税类设置</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ admin_route('tax_rates.index') }}">税率设置</a>
    </li>
  </ul>

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
            <th>描述</th>
            <th>创建时间</th>
            <th>修改时间</th>
            <th class="text-end">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="tax, index in tax_classes" :key="index">
            <td>@{{ tax.id }}</td>
            <td>@{{ tax.title }}</td>
            <td>@{{ tax.description }}</td>
            <td>@{{ tax.created_at }}</td>
            <td>@{{ tax.updated_at }}</td>
            <td class="text-end">
              <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">编辑</button>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(tax.id, index)">删除</button>
            </td>
          </tr>
        </tbody>
      </table>

      {{-- {{ $tax_classes->links('admin::vendor/pagination/bootstrap-4') }} --}}
    </div>

    <el-dialog title="创建税费" :visible.sync="dialog.show" width="700px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        <el-form-item label="税类名称" prop="title">
          <el-input v-model="dialog.form.title" placeholder="税类名称"></el-input>
        </el-form-item>

        <el-form-item label="描述" prop="description">
          <el-input v-model="dialog.form.description" placeholder="描述"></el-input>
        </el-form-item>

        <el-form-item label="规则">
            <table class="table table-bordered" style="line-height: 1.6;">
              <thead>
                <tr>
                  <th>税率</th>
                  <th>基于</th>
                  <th>优先级</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="rule, index in dialog.form.tax_rules" :key="index">
                  <td>
                    <el-select v-model="rule.tax_rate_id" size="mini" placeholder="请选择">
                      <el-option v-for="tax in source.all_tax_rates" :key="tax.id" :label="tax.name" :value="tax.id"></el-option>
                    </el-select>
                  </td>
                  <td>
                    <el-select v-model="rule.based" size="mini" placeholder="请选择">
                      <el-option v-for="base in source.bases" :key="base" :label="base" :value="base"></el-option>
                    </el-select>
                  </td>
                  <td width="80px"><el-input v-model="rule.priority" size="mini" placeholder="优先级"></el-input></td>
                  <td>
                    <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteRates(index)">删除</button>
                  </td>
                </tr>
              </tbody>
            </table>
            <el-button type="primary" icon="el-icon-plus" size="small" plain @click="addRates">添加规则</el-button>
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
  <script>
    new Vue({
      el: '#tax-classes-app',

      data: {
        tax_classes: @json($tax_classes ?? []),

        source: {
          all_tax_rates: @json($all_tax_rates ?? []),
          bases: @json($bases ?? []),
        },

        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            id: null,
            title: '',
            description: '',
            tax_rules: [],
          },
        },

        rules: {
          title: [{required: true,message: '请输入税类名称',trigger: 'blur'}, ],
          description: [{required: true,message: '请输入描述',trigger: 'blur'}, ],
        }
      },

      beforeMount() {
        // this.source.languages.forEach(e => {
        //   this.$set(this.dialog.form.name, e.code, '')
        //   this.$set(this.dialog.form.description, e.code, '')
        // })
      },

      methods: {
        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index

          if (type == 'edit') {
            let tax = this.tax_classes[index];

            this.dialog.form = {
              id: tax.id,
              title: tax.title,
              description: tax.description,
              tax_rules: tax.tax_rules,
            }
          }
        },

        addRates() {
          const tax_rate_id = this.source.all_tax_rates[0]?.id || 0;
          const based = this.source.bases[0] || '';

          this.dialog.form.tax_rules.push({tax_rate_id, based, priority: ''})
        },

        deleteRates(index) {
          this.dialog.form.tax_rules.splice(index, 1)
        },

        addFormSubmit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'tax_classes' : 'tax_classes/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.type == 'add') {
                this.tax_classes.push(res.data)
              } else {
                this.tax_classes[this.dialog.index] = res.data
              }

              this.dialog.show = false
            })
          });
        },

        deleteCustomer(id, index) {
          const self = this;
          this.$confirm('确定要删除税类码？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            $http.delete('tax_classes/' + id).then((res) => {
              this.$message.success(res.message);
              self.tax_classes.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.form.tax_rules = []
          this.dialog.show = false
        }
      }
    })
  </script>
@endpush
