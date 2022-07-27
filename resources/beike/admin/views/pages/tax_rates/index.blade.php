@extends('admin::layouts.master')

@section('title', '税率设置')

@section('content')
  <ul class="nav-bordered nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link" aria-current="page" href="{{ admin_route('tax_classes.index') }}">税类设置</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="{{ admin_route('tax_rates.index') }}">税率设置</a>
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
            <th>税种</th>
            <th>税率</th>
            <th>类型</th>
            <th>区域</th>
            <th>创建时间</th>
            <th>修改时间</th>
            <th class="text-end">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="tax, index in tax_rates" :key="index">
            <td>@{{ tax.id }}</td>
            <td>@{{ tax.name }}</td>
            <td>@{{ tax.rate }}</td>
            <td>@{{ tax.type }}</td>
            <td>@{{ tax.region.name }}</td>
            <td>@{{ tax.created_at }}</td>
            <td>@{{ tax.updated_at }}</td>
            <td class="text-end">
              <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">编辑</button>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(tax.id, index)">删除</button>
            </td>
          </tr>
        </tbody>
      </table>

      {{-- {{ $tax_rates->links('admin::vendor/pagination/bootstrap-4') }} --}}
    </div>

    <el-dialog title="税率" :visible.sync="dialog.show" width="500px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        <el-form-item label="税种" prop="name">
          <el-input v-model="dialog.form.name" placeholder="税种"></el-input>
        </el-form-item>

        <el-form-item label="税率" prop="rate">
          <el-input v-model="dialog.form.rate" placeholder="税率"></el-input>
        </el-form-item>

        <el-form-item label="类型">
          <el-select v-model="dialog.form.type" size="small" placeholder="请选择">
            <el-option v-for="type in source.types" :key="type.value" :label="type.name" :value="type.value"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="区域">
          <el-select v-model="dialog.form.geo_zone_id" size="small" placeholder="请选择">
            <el-option v-for="region in source.regions" :key="region.value" :label="region.name" :value="region.id"></el-option>
          </el-select>
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
        tax_rates: @json($tax_rates ?? []),

        source: {
          all_tax_rates: @json($all_tax_rates ?? []),
          regions: @json($regions ?? []),
          types: [{value:'percent', name: '百分比'}, {value:'flat', name: '固定税率'}]
        },

        dialog: {
          show: false,
          index: null,
          type: 'add',
          form: {
            id: null,
            name: '',
            rate: '',
            type: 'percent',
            geo_zone_id: '',
          },
        },

        rules: {
          name: [{required: true,message: '请输入税种',trigger: 'blur'}, ],
          rate: [{required: true,message: '请输入税率',trigger: 'blur'}, ],
        }
      },

      methods: {
        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index
          this.dialog.form.geo_zone_id = this.source.regions[0].id

          if (type == 'edit') {
            let tax = this.tax_rates[index];

            this.dialog.form = {
              id: tax.id,
              name: tax.name,
              rate: tax.rate,
              type: tax.type,
              geo_zone_id: tax.geo_zone_id,
            }
          }
        },

        deleteRates(index) {
          this.dialog.form.tax_rules.splice(index, 1)
        },

        addFormSubmit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'tax_rates' : 'tax_rates/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (type == 'add') {
                this.tax_rates.push(res.data)
              } else {
                this.tax_rates[this.dialog.index] = res.data
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
            $http.delete('tax_rates/' + id).then((res) => {
              this.$message.success(res.message);
              self.tax_rates.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.form.type = 'percent';
          this.dialog.show = false
        }
      }
    })
  </script>
@endpush
