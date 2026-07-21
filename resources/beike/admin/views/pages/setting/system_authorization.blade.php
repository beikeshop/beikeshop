@extends('admin::layouts.master')

@section('title', __('admin/common.settings.system_authorization'))

@section('content-area-class', 'w-max-1200')

@section('page-title-back', true)

@section('content')
  <div class="card h-min-600">
    <div class="card-body">
      <x-admin-form-input name="license_code" title="{{ __('admin/setting.license_code') }}" value="{{ old('license_code', system_setting('base.license_code', '')) }}" :readonly="true" />

      <button class="btn btn-primary get-license-code" type="button">{{ __('admin/setting.license_code_get') }}</button>
    </div>
  </div>
@endsection
