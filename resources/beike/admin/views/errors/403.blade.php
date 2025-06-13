@extends('admin::layouts.master')

@section('title', __('admin/common.forbidden'))
@section('code', '403')

@section('content')
<div id="customer-app" class="card h-min-600">
  <div class="d-flex flex-column align-center align-items-center mb-4">
    <img src="{{ asset('image/no-data.svg') }}" class="img-fluid wp-300 my-3">
    <div class="text-secondary fs-5">{{ __('admin/common.has_no_permission') }}</div>
  </div>
</div>
@endsection