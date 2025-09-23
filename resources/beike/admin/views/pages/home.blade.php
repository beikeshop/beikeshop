@extends('admin::layouts.master')

@section('title', __('admin/common.admin_panel'))

@section('body-class', 'admin-home')

@section('content')
  @if (system_setting('base.guide', '1'))
    @include('admin::pages.dashboard.guide')
  @endif

  @hookwrapper('admin.home.dashboard.totals')
  @include('admin::pages.dashboard.totals')
  @endhookwrapper

  @hookwrapper('admin.home.dashboard.orders_chart')
  @include('admin::pages.dashboard.orders_chart')
  @endhookwrapper

  @hookwrapper('admin.home.index.content.footer')
  @hook('admin.home.index.content.footer')
  @endhookwrapper
@endsection
