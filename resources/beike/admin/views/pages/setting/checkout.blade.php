@extends('admin::layouts.master')

@section('title', __('admin/common.settings.checkout'))

@section('content-area-class', 'w-max-1200')

@section('page-title-back', admin_route('settings.index'))

@section('head-form-btns', true)

@section('content')
  @if (session('success'))
    <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
  @endif
  @if (session('error'))
    <div class="alert alert-danger">
      {!! session('error') !!}
    </div>
  @endif
  <div class="card h-min-600">
    <div class="card-body">
      @hook('admin.setting.checkout.content.before')
      <form action="{{ admin_route('settings.store') }}" class="needs-validation" novalidate method="POST" id="form-app">
        @csrf
          <input type="hidden" name="return_url" value="{{ url()->full() }}"/>

          @hook('admin.setting.checkout.before')
          <x-admin-form-switch name="show_price_after_login" title="{{ __('admin/setting.show_price_after_login') }}" value="{{ old('show_price_after_login', system_setting('base.show_price_after_login', '0')) }}">
            <div class="help-text font-size-12 lh-base show-price-error-text">{{ __('admin/setting.show_price_after_login_tips') }}</div>
          </x-admin-form-switch>

          <x-admin-form-switch name="guest_checkout" title="{{ __('admin/setting.guest_checkout') }}" value="{{ old('guest_checkout', system_setting('base.guest_checkout', '1')) }}" />

          <x-admin-form-switch name="customer_approved" title="{{ __('admin/setting.customer_approved') }}" value="{{ old('customer_approved', system_setting('base.customer_approved', '0')) }}">
          </x-admin-form-switch>

          <x-admin-form-switch name="address_phoner_equired" title="{{ __('admin/setting.address_phoner_equired') }}" value="{{ old('address_phoner_equired', system_setting('base.address_phoner_equired', '0')) }}" />

          <x-admin-form-switch name="address_post_code" title="{{ __('admin/setting.address_post_code') }}" value="{{ old('address_post_code', system_setting('base.address_post_code', '0')) }}" />

          <x-admin-form-switch name="tax" title="{{ __('admin/setting.enable_tax') }}" value="{{ old('tax', system_setting('base.tax', '0')) }}">
            <div class="help-text font-size-12 lh-base">{{ __('admin/setting.enable_tax_info') }}</div>
          </x-admin-form-switch>

          <x-admin-form-select title="{{ __('admin/setting.tax_address') }}" name="tax_address" :value="old('tax_address', system_setting('base.tax_address', 'shipping'))" :options="$tax_address">
          </x-admin-form-select>

          <x-admin-form-input name="rate_api_key" title="{{ __('admin/setting.rate_api_key') }}" value="{{ old('rate_api_key', system_setting('base.rate_api_key', '')) }}">
            <div class="help-text font-size-12 lh-base">
              <a class="text-secondary" href="https://www.exchangerate-api.com/" target="_blank">www.exchangerate-api.com</a>
            </div>
          </x-admin-form-input>

          <x-admin::form.row :title="__('admin/setting.order_auto_cancel')">
            <div class="input-group wp-400">
              <input type="number" value="{{ old('order_auto_cancel', system_setting('base.order_auto_cancel', '')) }}" name="order_auto_cancel" class="form-control" placeholder="{{ __('admin/setting.order_auto_cancel') }}">
              <span class="input-group-text">{{ __('common.text_hour') }}</span>
            </div>
          </x-admin::form.row>

          <x-admin::form.row :title="__('admin/setting.order_auto_complete')">
            <div class="input-group wp-400">
              <input type="number" value="{{ old('order_auto_complete', system_setting('base.order_auto_complete', '')) }}" name="order_auto_complete" class="form-control" placeholder="{{ __('admin/setting.order_auto_complete') }}">
              <span class="input-group-text">{{ __('common.text_hour') }}</span>
            </div>
          </x-admin::form.row>
          @hook('admin.setting.checkout.after')

        <x-admin::form.row title="">
          <button type="submit" class="btn btn-primary d-none mt-4">{{ __('common.submit') }}</button>
        </x-admin::form.row>
      </form>
      @hook('admin.setting.checkout.content.after')
    </div>
  </div>
@endsection




