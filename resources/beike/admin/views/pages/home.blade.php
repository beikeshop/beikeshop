@extends('admin::layouts.master')

@section('title', '')

@section('body-class', 'admin-home')

@section('content')
  <div class="">
    @hook('admin.home.index.content.header')

    @if (system_setting('base.guide', '1'))
      @include('admin::pages.dashboard.guide')
    @endif

    @include('admin::pages.dashboard.charts_data')

    @hook('admin.home.index.content.footer')
  </div>
@endsection
