@extends('layout.master')

@section('body-class', 'page-account-address')

@push('header')
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
@endpush

@section('content')
  <div class="container" id="address-app">

    <x-shop-breadcrumb type="static" value="account.addresses.index" />

    <div class="row">
      <x-shop-sidebar />

      <div class="col-12 col-md-9">
        <div class="card h-min-600">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">{{ __('shop/account.addresses.index') }}</h5>
          </div>
          <div class="card-body h-600">
            <button v-if="addresses.length" class="btn btn-dark mb-3" @click="editAddress"><i class="bi bi-plus-square-dotted me-1"></i>
              {{ __('shop/account.addresses.add_address') }}</button>
            <div class="addresses-wrap" v-cloak>
              <div class="row" v-if="addresses.length">
                <div class="col-6" v-for="address, index in addresses" :key="index">
                  <div class="item">
                    <div class="name-wrap">
                      <span class="name">@{{ address.name }}</span>
                      <span class="phone">@{{ address.phone }}</span>
                    </div>
                    <div class="zipcode">@{{ address.zipcode }}</div>
                    <div class="address-info">@{{ address.country }} @{{ address.zone }} @{{ address.city }}
                      @{{ address.address_1 }}</div>
                    <div class="address-bottom">
                      <div><span class="badge bg-success"
                          v-if="address.default">{{ __('shop/account.addresses.default_address') }}</span></div>
                      <div>
                        <a class="me-2" @click.stop="deleteAddress(index)">{{ __('shop/account.addresses.delete') }}</a>
                        <a @click.stop="editAddress(index)">{{ __('shop/account.addresses.edit') }}</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else class="text-center">
                <x-shop-no-data />
                <button class="btn btn-dark mb-3" @click="editAddress"><i class="bi bi-plus-square-dotted me-1"></i>
                  {{ __('shop/account.addresses.add_address') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('shared.address-form', [
        'address_form_key' => 'form',
        'address_form_show' => 'editShow',
        'address_form_rules' => 'rules',
    ])
  </div>
@endsection

@push('add-scripts')
  <script>
    new Vue({
      el: '#address-app',

      data: {
        editIndex: null,

        editShow: false,

        form: {
          name: '',
          phone: '',
          country_id: @json((int) system_setting('base.country_id')),
          zipcode: '',
          zone_id: @json((int) system_setting('base.zone_id')),
          city: '',
          address_1: '',
          address_2: '',
          default: false,
        },

        addresses: @json($addresses ?? []),

        source: {
          countries: @json($countries ?? []),
          zones: []
        },

        rules: {
          name: [{
            required: true,
            message: '{{ __('shop/account.addresses.enter_name') }}',
            trigger: 'blur'
          }, ],
          phone: [{
            required: true,
            message: '{{ __('shop/account.addresses.enter_phone') }}',
            trigger: 'blur'
          }, ],
          address_1: [{
            required: true,
            message: ' {{ __('shop/account.addresses.enter_address') }}',
            trigger: 'blur'
          }, ],
          zone_id: [{
            required: true,
            message: '{{ __('shop/account.addresses.select_province') }}',
            trigger: 'blur'
          }, ],
          city: [{
            required: true,
            message: '{{ __('shop/account.addresses.enter_city') }}',
            trigger: 'blur'
          }, ],
        }
      },

      // 实例被挂载后调用
      mounted() {},

      beforeMount() {
        this.countryChange(this.form.country_id);
      },

      methods: {
        editAddress(index) {
          if (typeof index == 'number') {
            this.editIndex = index;
            this.form = JSON.parse(JSON.stringify(this.addresses[index]))
            this.countryChange(this.form.country_id);
          }

          this.editShow = true
        },

        deleteAddress(index) {
          this.$confirm('{{ __('shop/account.addresses.confirm_delete') }}',
            '{{ __('shop/account.addresses.hint') }}', {
              confirmButtonText: '{{ __('common.confirm') }}',
              cancelButtonText: '{{ __('common.cancel') }}',
              type: 'warning'
            }).then(() => {
            $http.delete('/account/addresses/' + this.addresses[index].id).then((res) => {
              this.$message.success(res.message);
              this.addresses.splice(index, 1)
            })
          }).catch(() => {})
        },

        addressFormSubmit(form) {
          const self = this;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{ __('shop/account.addresses.check_form') }}');
              return;
            }

            const type = this.form.id ? 'put' : 'post';
            const url = `/account/addresses${type == 'put' ? '/' + this.form.id : ''}`;

            $http[type](url, this.form).then((res) => {
              if (res.data.default) {
                this.addresses.map(e => e.default = false)
              }

              if (type == 'post') {
                this.addresses.push(res.data)
              } else {
                this.addresses[this.editIndex] = res.data
              }
              this.$message.success(res.message);
              this.$refs[form].resetFields();
              this.editShow = false
              this.editIndex = null;
            })
          });
        },

        closeAddressDialog(form) {
          this.$refs[form].resetFields();
          this.editShow = false
          this.editIndex = null;

          Object.keys(this.form).forEach(key => this.form[key] = '')
          this.form.country_id = @json((int) system_setting('base.country_id'))
        },

        countryChange(e) {
          const self = this;

          $http.get(`/countries/${e}/zones`, null, {
            hload: true
          }).then((res) => {
            this.source.zones = res.data.zones;

            if (!res.data.zones.some(e => e.id == this.form.zone_id)) {
              this.form.zone_id = '';
            }
          })
        },
      }
    })
  </script>
@endpush
