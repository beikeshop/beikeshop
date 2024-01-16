@extends('admin::layouts.master')

@section('title', __('admin/region.index'))

@section('content')
<div id="tax-classes-app" class="card" v-cloak>
  <div class="card-body h-min-600">
    <div class="d-flex justify-content-between mb-4">
      <button type="button" class="btn btn-primary" @click="checkedCreate('add', null)">{{ __('common.add') }}</button>
    </div>
    <div class="table-push">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>{{ __('admin/region.name') }}</th>
            <th>{{ __('admin/region.describe') }}</th>
            <th>{{ __('common.created_at') }}</th>
            <th>{{ __('common.updated_at') }}</th>
            <th class="text-end">{{ __('common.action') }}</th>
          </tr>
        </thead>
        <tbody v-if="regions.length">
          <tr v-for="tax, index in regions" :key="index">
            <td>@{{ tax.id }}</td>
            <td>@{{ tax.name }}</td>
            <td :title="tax.description">@{{ stringLengthInte(tax.description) }}</td>
            <td>@{{ tax.created_at }}</td>
            <td>@{{ tax.updated_at }}</td>
            <td class="text-end">
              <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">{{
                __('common.edit') }}</button>
              <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(tax.id, index)">{{
                __('common.delete') }}</button>
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="6" class="border-0">
              <x-admin-no-data />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <el-dialog title="{{ __('admin/region.regions_create') }}" :visible.sync="dialog.show" width="700px"
    @close="closeCustomersDialog('form')" :close-on-click-modal="false" @open="openDialog">

    <el-form ref="form" :rules="rules" :model="dialog.form" label-width="120px">
      <el-form-item label="{{ __('common.name') }}" prop="name">
        <el-input v-model="dialog.form.name" placeholder="{{ __('common.name') }}"></el-input>
      </el-form-item>

      <el-form-item label="{{ __('admin/region.describe') }}" prop="description">
        <el-input v-model="dialog.form.description" placeholder="{{ __('admin/region.describe') }}"></el-input>
      </el-form-item>

      <el-form-item label="{{ __('admin/region.index') }}">
        <table class="table table-bordered" style="line-height: 1.6;">
          <thead>
            <tr>
              <th>{{ __('admin/region.country') }}</th>
              <th>{{ __('admin/region.zone') }}</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="rule, index in dialog.form.region_zones" :key="index">
              <td>
                <el-select v-model="rule.country_id" size="mini" filterable
                  placeholder="{{ __('admin/customer.choose_country') }}" @change="(e) => {countryChange(e, index)}">
                  <el-option v-for="item, option_index in source.countries" :key="index + '-' + option_index" :label="item.name" :value="item.id">
                  </el-option>
                </el-select>
              </td>
              <td>
                <el-select v-model="rule.zone_id" size="mini" filterable
                  placeholder="{{ __('admin/customer.choose_zones') }}">
                  <el-option v-for="item, option_index in rule.zones" :key="index + '-' + option_index" :label="item.name" :value="item.id">
                  </el-option>
                </el-select>
              </td>
              <td>
                <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteRates(index)">{{
                  __('common.delete') }}</button>
              </td>
            </tr>
          </tbody>
        </table>
        <el-button type="primary" icon="el-icon-plus" size="small" plain @click="addRates">{{ __('common.add') }}
        </el-button>
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
            name: '',
            description: '',
            region_zones: [],
          },
        },

        rules: {
          name: [{required: true,message: '{{ __('common.error_required', ['name' => __('common.name')]) }}',trigger: 'blur'}, ],
          description: [{required: true,message: '{{ __('common.error_required', ['name' => __('admin/region.describe')]) }}',trigger: 'blur'}, ],
        }
      },

      // 在挂载开始之前被调用:相关的 render 函数首次被调用
      beforeMount() {
        $http.get(`countries/${this.source.country_id}/zones`).then((res) => {
          this.dialog.zones = [
            {name: '{{ __('common.please_choose') }}', id: 0},
            ...res.data.zones
          ]
        })
      },

      methods: {
        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index
        },

        openDialog() {
          if (this.dialog.type == 'edit') {
            let tax = this.regions[this.dialog.index];

            tax.region_zones.forEach(e => {
              $http.get(`countries/${e.country_id}/zones`).then((res) => {
                let zones = [{name: '{{ __('common.please_choose') }}', id: 0}, ...res.data.zones]
                this.$set(e, 'zones', zones)
              })
            })

            this.dialog.form = {
              id: tax.id,
              name: tax.name,
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
            zone_id: 0,
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
              this.$message.error('{{ __('common.error_form') }}');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.type == 'add') {
                this.regions.push(res.data)
              } else {
                this.regions[this.dialog.index] = res.data
              }
              this.dialog.show = false
            })
          });
        },

        countryChange(e, index) {
          $http.get(`countries/${e}/zones`).then((res) => {
            this.dialog.form.region_zones[index].zones = [
              {name: '{{ __('common.please_choose') }}', id: 0},
              ...res.data.zones
            ]
            this.dialog.form.region_zones[index].zone_id = 0
          })
        },

        deleteCustomer(id, index) {
          const self = this;
          this.$confirm('{{ __('common.confirm_delete') }}', '{{ __('common.text_hint') }}', {
            confirmButtonText: '{{ __('common.confirm') }}',
            cancelButtonText: '{{ __('common.cancel') }}',
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