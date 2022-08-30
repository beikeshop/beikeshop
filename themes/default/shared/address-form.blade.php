@php
  $address_form_show = $address_form_show ?? 'dialogAddress.show';
  $address_form_key = $address_form_key ?? 'dialogAddress.form';
  $address_form_rules = $address_form_rules ?? 'addressRules';
@endphp

<el-dialog custom-class="mobileWidth" title="{{ __('address.index') }}" :visible.sync="{{ $address_form_show }}" @close="closeAddressDialog('addressForm')" :close-on-click-modal="false">
  <el-form ref="addressForm" :rules="{{ $address_form_rules }}" :model="{{ $address_form_key }}" label-width="100px">
    <el-form-item label="{{ __('address.name') }}" prop="name">
      <el-input v-model="{{ $address_form_key }}.name"></el-input>
    </el-form-item>
    <el-form-item label="{{ __('address.phone') }}" prop="phone">
      <el-input maxlength="11" v-model="{{ $address_form_key }}.phone" type="number"></el-input>
    </el-form-item>
    <el-form-item label="{{ __('address.address') }}" required>
      <div class="row dialog-address">
        <div class="col-4">
          <el-form-item>
            <el-select v-model="{{ $address_form_key }}.country_id" filterable placeholder="{{ __('address.country_id') }}" @change="countryChange">
              <el-option v-for="item in source.countries" :key="item.id" :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
        </div>
        <div class="col-4 mt-2 mt-sm-0">
          <el-form-item prop="zone_id">
            <el-select v-model="{{ $address_form_key }}.zone_id" filterable placeholder="{{ __('address.zone') }}">
              <el-option v-for="item in source.zones" :key="item.id" :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
        </div>
        <div class="col-4 mt-2 mt-sm-0">
          <el-form-item prop="city">
            <el-input v-model="{{ $address_form_key }}.city" placeholder="{{ __('shop/account.addresses.enter_city') }}"></el-input>
          </el-form-item>
        </div>
      </div>
    </el-form-item>
    <el-form-item label="{{ __('address.post_code') }}" prop="zipcode">
      <el-input v-model="{{ $address_form_key }}.zipcode"></el-input>
    </el-form-item>
    <el-form-item label="{{ __('address.address_1') }}" prop="address_1">
      <el-input v-model="{{ $address_form_key }}.address_1"></el-input>
    </el-form-item>
    <el-form-item label="{{ __('address.address_2') }}">
      <el-input v-model="{{ $address_form_key }}.address_2"></el-input>
    </el-form-item>
    <el-form-item label="{{ __('address.default') }}">
      <el-switch
        v-model="{{ $address_form_key }}.default"
        >
      </el-switch>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" @click="addressFormSubmit('addressForm')">{{ __('common.save') }}</el-button>
      <el-button @click="closeAddressDialog('addressForm')">{{ __('common.cancel') }}</el-button>
    </el-form-item>
  </el-form>
</el-dialog>