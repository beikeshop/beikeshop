@extends('admin::layouts.master')

@section('title', __('admin/common.help'))

@section('body-class', 'help-page')

@section('content')
<div class="top-links-wrap">
  <div class="container">
      @if (system_setting('base.guide', '1'))
        @include('admin::pages.dashboard.guide')
      @endif

      <div class="help-title">{{ __('admin/help.title_1') }}</div>
      <div class="row">
        @foreach ($top_links as $item)
        <div class="col-lg-3 col-6">
          <div class="top-links-info">
            <div class="icon"><span>{!! $item['icon'] !!}</span></div>
            <div class="title">{{ $item['title'] }}</div>
            <a href="{{ $item['url'] }}" class="btn btn-outline-primary" target="_blank">{{ __('admin/help.btn_link') }}</a>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  <div class="links-wrap">
    <div class="container">
      <div class="help-title">{{ __('admin/help.title_2') }}</div>
      <div class="row">
        @foreach ($links as $item)
          <div class="col-lg-4 col-12">
            <div class="help-links-wrap">
              <div class="top">
                <div class="title-wrap">
                  <div class="icon"><i class="bi bi-link-45deg"></i></div>
                  <div class="title">{{ $item['title'] }}</div>
                </div>
              </div>
              <div class="description">{{ $item['description'] }}</div>
              <a href="{{ $item['url'] }}" class="link" target="_blank">{{ __('admin/help.btn_link') }} <i class="bi bi-chevron-right"></i></a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endsection
