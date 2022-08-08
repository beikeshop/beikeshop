@extends('admin::layouts.master')

@section('title', '信息页面')

@section('content')
  <div id="plugins-app-form" class="card h-min-600">
    <div class="card-body">
      <h6 class="border-bottom pb-3 mb-4">编辑信息页面</h6>
      <form action="{{ admin_route('settings.store') }}" method="POST" id="app">
        @csrf
        <x-admin-form-input name="admin_name" title="后台目录" value="{{ old('admin_name', system_setting('base.admin_name', 'admin')) }}">
          <div class="help-text font-size-12 lh-base">管理后台目录,默认为admin</div>
        </x-admin-form-input>
      </form>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    $(function() {

    });
  </script>
@endpush



