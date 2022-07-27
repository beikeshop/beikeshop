@extends('admin::layouts.master')

@section('title', '区域组')

@section('content')
  <div id="tax-classes-app" class="card" v-cloak>
    <div class="card-body">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCreate('add', null)">添加</button>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>名称</th>
            <th class="text-end">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="tax, index in regions" :key="index">
            <td>@{{ tax.id }}</td>
            <td>@{{ tax.name }}</td>
            <td class="text-end">
              <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">编辑</button>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(tax.id, index)">删除</button>
            </td>
          </tr>
        </tbody>
      </table>

      {{-- {{ $regions->links('admin::vendor/pagination/bootstrap-4') }} --}}
    </div>

    <el-dialog title="创建税费" :visible.sync="dialog.show" width="700px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="120px">
        <el-form-item label="区域群组名称" prop="title">
          <el-input v-model="dialog.form.title" placeholder="区域群组名称"></el-input>
        </el-form-item>

        <el-form-item label="描述" prop="description">
          <el-input v-model="dialog.form.description" placeholder="描述"></el-input>
        </el-form-item>

        <el-form-item label="区域群组">
            <table class="table table-bordered" style="line-height: 1.6;">
              <thead>
                <tr>
                  <th>国家</th>
                  <th>省份</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="rule, index in dialog.form.region_zones" :key="index">
                  <td>
                    <el-select v-model="rule.country_id" size="mini" filterable placeholder="选择国家" @change="(e) => {countryChange(e, index)}">
                      <el-option v-for="item in source.countries" :key="item.id" :label="item.name"
                        :value="item.id">
                      </el-option>
                    </el-select>
                  </td>
                  <td>
                    <el-select v-model="rule.zone_id" size="mini" filterable placeholder="选择省份">
                      <el-option v-for="item in rule.zones" :key="item.id" :label="item.name"
                        :value="item.id">
                      </el-option>
                    </el-select>
                  </td>
                  <td>
                    <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteRates(index)">删除</button>
                  </td>
                </tr>
              </tbody>
            </table>
            <el-button type="primary" icon="el-icon-plus" size="small" plain @click="addRates">添加区域</el-button>
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
        regions: @json($regions ?? []),

        source: {
          all_tax_rates: @json($all_tax_rates ?? []),
          bases: @json($bases ?? []),
          countries: @json($countries ?? []),
          country_id: @json((int)system_setting('base.country_id')),
        },

        dialog: {
          show: false,
          index: null,
          type: 'add',
          zones: [],
          form: {
            id: null,
            title: '',
            description: '',
            region_zones: [],
          },
        },

        rules: {
          title: [{required: true,message: '请输入区域群组名称',trigger: 'blur'}, ],
          description: [{required: true,message: '请输入描述',trigger: 'blur'}, ],
        }
      },

      // 在挂载开始之前被调用:相关的 render 函数首次被调用
      beforeMount() {
        $http.get(`countries/${this.source.country_id}/zones`).then((res) => {
          this.dialog.zones = res.data.zones
        })
      },

      methods: {
        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type

          if (type == 'edit') {
            let tax = this.regions[index];

            this.dialog.form = {
              id: tax.id,
              title: tax.title,
              description: tax.description,
              region_zones: tax.region_zones,
            }
          }
        },

        addRates() {
          const tax_rate_id = this.source.all_tax_rates[0]?.id || 0;
          const based = this.source.bases[0] || '';

          this.dialog.form.region_zones.push({
            country_id: this.source.country_id,
            zone_id: '',
            zones: this.dialog.zones,
          })
        },

        deleteRates(index) {
          this.dialog.form.region_zones.splice(index, 1)
        },

        addFormSubmit(form) {
          const self = this;
          const type = this.dialog.type == 'add' ? 'post' : 'put';
          const url = this.dialog.type == 'add' ? 'regions' : 'regions/' + this.dialog.form.id;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              this.regions.push(res.data)
              this.dialog.show = false
            })
          });
        },

        countryChange(e, index) {
          $http.get(`countries/${e}/zones`).then((res) => {
            this.dialog.form.region_zones[index].zones = res.data.zones
          })
        },

        deleteCustomer(id, index) {
          const self = this;
          this.$confirm('确定要删除税类码？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            $http.delete('regions/' + id).then((res) => {
              this.$message.success(res.message);
              self.regions.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.form.region_zones = []
          this.dialog.show = false
        }
      }
    })
  </script>
@endpush
