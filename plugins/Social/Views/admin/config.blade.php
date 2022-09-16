@extends('admin::layouts.master')

@section('title', __('admin/plugin.plugins_show'))

@section('content')
  <div class="card">
    <div class="card-body">
      <h6 class="border-bottom pb-3 mb-4">{{ $plugin->name }}</h6>

      @if (session('success'))
        <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
      @endif
      <form class="needs-validation" novalidate action="{{ admin_route('plugins.update', [$plugin->code]) }}" method="POST">
        @csrf
        {{ method_field('put') }}

这里是social配置模板

        <x-admin::form.row title="">
          <button type="submit" class="btn btn-primary btn-lg mt-4">{{ __('common.submit') }}</button>
        </x-admin::form.row>
      </form>
    </div>
  </div>
@endsection
