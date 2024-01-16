<template id="address-dialog">
  <div class="address-dialog">
    <el-dialog custom-class="mobileWidth" title="{{ __('address.index') }}" :visible.sync="editShow" @close="closeAddressDialog('addressForm')" :close-on-click-modal="false">
      <el-form ref="addressForm" :rules="rules" label-position="top" :model="form" label-width="100px">
        <div class="d-flex">
          <el-form-item label="{{ __('address.name') }}" class="w-50" prop="name">
            <el-input v-model="form.name" placeholder="{{ __('address.name') }}"></el-input>
          </el-form-item>
          @if (!current_customer())
          <el-form-item label="{{ __('common.email') }}" prop="email" v-if="type == 'guest_shipping_address'" class="w-50 ms-3">
            <el-input v-model="form.email" placeholder="{{ __('common.email') }}"></el-input>
          </el-form-item>
          @else
          <el-form-item label="{{ __('address.address_1') }}"  class="w-50 ms-3" prop="address_1">
            <el-input v-model="form.address_1" placeholder="{{ __('address.address_1') }}"></el-input>
          </el-form-item>
          @endif
        </div>
        @if (!current_customer())
        <el-form-item label="{{ __('address.address_1') }}" prop="address_1">
          <el-input v-model="form.address_1" placeholder="{{ __('address.address_1') }}"></el-input>
        </el-form-item>
        @endif
        <div class="d-flex">
          <el-form-item label="{{ __('address.address_2') }}" class="w-50">
            <el-input v-model="form.address_2" placeholder="{{ __('address.address_2') }}"></el-input>
          </el-form-item>
          <el-form-item label="{{ __('address.post_code') }}" class="w-50 ms-3">
            <el-input v-model="form.zipcode" placeholder="{{ __('address.post_code') }}"></el-input>
          </el-form-item>
        </div>
        <div class="d-flex dialog-address">
          <el-form-item label="{{ __('address.phone') }}" class="w-50">
            <el-input maxlength="11" v-model="form.phone" type="number" placeholder="{{ __('address.phone') }}"></el-input>
          </el-form-item>
          <el-form-item prop="city" label="{{ __('shop/account/addresses.enter_city') }}" required class="w-50 ms-3">
            <el-input v-model="form.city" placeholder="{{ __('shop/account/addresses.enter_city') }}"></el-input>
          </el-form-item>
        </div>
        <div class="d-flex">
          <el-form-item label="{{ __('address.country') }}" required class="w-50">
            <el-select v-model="form.country_id" class="w-100" filterable placeholder="{{ __('address.country_id') }}" @change="countryChange">
              <el-option v-for="item in source.countries" :key="item.id" :label="item.name"
              :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="zone_id" label="{{ __('address.zone') }}" class="w-50 ms-3">
            <el-select v-model="form.zone_id" class="w-100" filterable placeholder="{{ __('address.zone') }}">
              <el-option v-for="item in source.zones" :key="item.id" :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
        </div>
        <el-form-item label="" v-if="source.isLogin">
          <span class="me-2">{{ __('address.default') }}</span> <el-switch v-model="form.default"></el-switch>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="addressFormSubmit('addressForm')">{{ __('common.save') }}</el-button>
          <el-button @click="closeAddressDialog('addressForm')">{{ __('common.cancel') }}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
  Vue.component('address-dialog', {
  template: '#address-dialog',
  props: {
    value: {
      default: null
    },
  },

  data: function () {
    return {
      editShow: false,
      index: null,
      type: 'shipping_address_id',
      form: {
        name: '',
        email: '',
        phone: '',
        country_id: @json((int) system_setting('base.country_id')),
        zipcode: '',
        zone_id: @json((int) system_setting('base.zone_id')),
        city: '',
        address_1: '',
        address_2: '',
        default: false,
      },

      rules: {
        name: [{
          required: true,
          message: '{{ __('shop/account/addresses.enter_name') }}',
          trigger: 'blur'
        }, ],
        email: [{
          required: true,
          type: 'email',
          message: '{{ __('shop/login.enter_email') }}',
          trigger: 'blur'
        }, ],
        address_1: [{
          required: true,
          message: ' {{ __('shop/account/addresses.enter_address') }}',
          trigger: 'blur'
        }, ],
        zone_id: [{
          required: true,
          message: '{{ __('shop/account/addresses.select_province') }}',
          trigger: 'blur'
        }, ],
        city: [{
          required: true,
          message: '{{ __('shop/account/addresses.enter_city') }}',
          trigger: 'blur'
        }, ],
      },

      source: {
        countries: @json($countries ?? []),
        zones: [],
        isLogin: config.isLogin,
      },
    }
  },

  computed: {
  },

  beforeMount() {
    this.countryChange(this.form.country_id);
  },

  methods: {
    editAddress(addresses, type) {
      this.type = type
      if (addresses) {
        this.form = addresses
      }

      this.countryChange(this.form.country_id);
      this.editShow = true
    },

    addressFormSubmit(form) {
      this.$refs[form].validate((valid) => {
        if (!valid) {
          this.$message.error('{{ __('shop/checkout.check_form') }}');
          return;
        }

        this.$emit('change', this.form)
        // const type = this.form.id ? 'put' : 'post';

        // const url = `/account/addresses${type == 'put' ? '/' + this.form.id : ''}`;

        // $http[type](url, this.form).then((res) => {
        //   this.$message.success(res.message);
        //   this.$emit('change', res.data)
        //   this.editShow = false
        // })
      });
    },

    closeAddressDialog() {
      this.$refs['addressForm'].resetFields();
      this.editShow = false

      Object.keys(this.form).forEach(key => this.form[key] = '')
      this.form.country_id = @json((int) system_setting('base.country_id'));
      this.form.default = false;
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
});
</script>