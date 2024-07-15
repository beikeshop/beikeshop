@extends('admin::layouts.master')

@section('title', __('admin/common.admin_panel'))

@section('body-class', 'admin-home')

@section('content')
  @if (system_setting('base.guide', '1'))
    @include('admin::pages.dashboard.guide')
  @endif
  @include('admin::pages.dashboard.totals')
  @include('admin::pages.dashboard.orders_chart')
@endsection
