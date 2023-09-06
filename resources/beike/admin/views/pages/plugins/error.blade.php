@extends('admin::layouts.master')

@section('title', __('admin/plugin.plugins_show'))

@section('content')
<div class="card h-min-600">
  <div class="card-body">
    <h6 class="border-bottom pb-3 mb-4">{{ $plugin->getLocaleName() }}</h6>
    <div class="alert alert-warning py-4 d-flex flex-column align-items-center" role="alert">
      <div class="mb-2"><i class="bi bi-exclamation-triangle-fill fs-2"></i></div>
      <div class="mb-4 fs-4">{{ $error }}</div>
      <a class="btn btn-primary btn-lg" href="{{ admin_route('marketing.show', $plugin_code) }}">{{ __('admin/common.to_buy')
        }}</a>
    </div>
  </div>
</div>
@endsection
