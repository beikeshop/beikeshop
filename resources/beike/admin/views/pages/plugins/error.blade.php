@extends('admin::layouts.master')

@section('title', __('admin/plugin.plugins_show'))

@section('content')
<div class="card h-min-600">
  <div class="card-body">
    <h6 class="border-bottom pb-3 mb-4">{{ $plugin->getLocaleName() }}</h6>
    {!! $error !!}
  </div>
</div>
@endsection
