@extends('admin::layouts.master')

@section('title', __('admin/tax_rate.index'))

@section('page-bottom-btns')
  <a href="{{ admin_route('settings.index') }}?tab=tab-checkout&line=tax_address" class="btn w-min-100 btn-outline-info" target="_blank">{{ __('admin/setting.tax_address') }}</a>
@endsection

@section('content')
  <ul class="nav-bordered nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link" aria-current="page" href="{{ admin_route('tax_classes.index') }}">{{ __('admin/tax_rate.tax_classes_index') }}</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="{{ admin_route('tax_rates.index') }}">{{ __('admin/tax_rate.index') }}</a>
    </li>
  </ul>

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
              <th>{{ __('admin/tax_rate.tax') }}</th>
              <th>{{ __('admin/tax_rate.tax_rate') }}</th>
              <th>{{ __('admin/tax_rate.type') }}</th>
              <th>{{ __('admin/tax_rate.area') }}</th>
              <th>{{ __('common.created_at') }}</th>
              <th>{{ __('common.updated_at') }}</th>
              <th class="text-end">{{ __('common.action') }}</th>
            </tr>
          </thead>
          <tbody v-if="tax_rates.length">
            <tr v-for="tax, index in tax_rates" :key="index">
              <td>@{{ tax.id }}</td>
              <td>@{{ tax.name }}</td>
              <td>@{{ tax.rate }}</td>
              <td>@{{ tax.type }}</td>
              <td>@{{ tax.region ? tax.region.name : '' }}</td>
              <td>@{{ tax.created_at }}</td>
              <td>@{{ tax.updated_at }}</td>
              <td class="text-end">
                <button class="btn btn-outline-secondary btn-sm" @click="checkedCreate('edit', index)">{{ __('common.edit') }}</button>
                <button class="btn btn-outline-danger btn-sm ml-1" type="button" @click="deleteCustomer(tax.id, index)">{{ __('common.delete') }}</button>
              </td>
            </tr>
          </tbody>
          <tbody v-else><tr><td colspan="7" class="border-0"><x-admin-no-data /></td></tr></tbody>
        </table>
      </div>

      {{-- {{ $tax_rates->links('admin::vendor/pagination/bootstrap-4') }} --}}
    </div>

    <el-dialog title="{{ __('admin/tax_rate.tax_rate') }}" :visible.sync="dialog.show" width="500px"
      @close="closeCustomersDialog('form')" :close-on-click-modal="false">

      <el-form ref="form" :rules="rules" :model="dialog.form" label-width="100px">
        <el-form-item label="{{ __('admin/tax_rate.tax') }}" prop="name">
          <el-input v-model="dialog.form.name" placeholder="{{ __('admin/tax_rate.tax') }}"></el-input>
        </el-form-item>

        <el-form-item label="{{ __('admin/tax_rate.tax_rate') }}" prop="rate">
          <el-input v-model="dialog.form.rate" placeholder="{{ __('admin/tax_rate.tax_rate') }}">
            <template slot="append" v-if="dialog.form.type == 'percent'">%</template>
          </el-input>
        </el-form-item>

        <el-form-item label="{{ __('admin/tax_rate.type') }}">
          <el-select v-model="dialog.form.type" size="small" placeholder="{{ __('common.please_choose') }}">
            <el-option v-for="type in source.types" :key="type.value" :label="type.name" :value="type.value"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="{{ __('admin/tax_rate.area') }}">
          <el-select v-model="dialog.form.region_id" size="small" placeholder="{{ __('common.please_choose') }}">
            <el-option v-for="region in source.regions" :key="region.value" :label="region.name" :value="region.id"></el-option>
          </el-select>
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
        tax_rates: @json($tax_rates ?? []),

        source: {
          all_tax_rates: @json($all_tax_rates ?? []),
          regions: @json($regions ?? []),
          types: [{value:'percent', name: '{{ __('admin/tax_rate.percentage') }}'}, {value:'flat', name: '{{ __('admin/tax_rate.fixed_tax_rate') }}'}]
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
            region_id: '',
          },
        },

        rules: {
          name: [{required: true,message: '{{ __('common.error_required', ['name' => __('admin/tax_rate.tax')])}}',trigger: 'blur'}, ],
          rate: [{required: true,message: '{{ __('common.error_required', ['name' => __('admin/tax_rate.tax_rate')])}}',trigger: 'blur'}, ],
        }
      },

      methods: {
        checkedCreate(type, index) {
          this.dialog.show = true
          this.dialog.type = type
          this.dialog.index = index
          this.dialog.form.region_id = this.source.regions.length ? this.source.regions[0].id : 0

          if (type == 'edit') {
            let tax = this.tax_rates[index];

            this.dialog.form = {
              id: tax.id,
              name: tax.name,
              rate: tax.rate,
              type: tax.type,
              region_id: tax.region_id,
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
              this.$message.error('{{ __('common.error_form') }}');
              return;
            }

            $http[type](url, this.dialog.form).then((res) => {
              this.$message.success(res.message);
              if (this.dialog.type == 'add') {
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
          this.$confirm('{{ __('common.confirm_delete') }}', '{{ __('common.text_hint') }}', {
            confirmButtonText: '{{ __('common.confirm') }}',
            cancelButtonText: '{{ __('common.cancel') }}',
            type: 'warning'
          }).then(() => {
            $http.delete('tax_rates/' + id).then((res) => {
              this.$message.success(res.message);
              self.tax_rates.splice(index, 1)
            })
          }).catch(()=>{})
        },

        closeCustomersDialog(form) {
          this.$refs[form].resetFields();
          Object.keys(this.dialog.form).forEach(key => this.dialog.form[key] = '')
          this.dialog.form.type = 'percent';
          this.dialog.show = false
        }
      }
    })
  </script>
@endpush
