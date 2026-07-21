@extends('admin::layouts.master')

@section('title', __('admin/setting.index'))

@push('header')
  <script src="{{ asset('vendor/cropper/cropper.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/cropper/cropper.min.css') }}">
@endpush

@section('content')
  @if ($errors->has('error'))
    <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4"/>
  @endif

  @if (session()->has('success'))
    <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
  @endif

  @foreach ($links as $item)
  <div class="card setting-card mb-4">
    <div class="card-header"><h6 class="card-title">{{ $item['title'] }}</h6></div>
    <div class="card-body">
      <div class="row g-3 g-md-4">
        @foreach ($item['child'] as $child)
          <div class="col-6 col-md-3">
            <a href="{{ $child['url'] }}" class="item text-dark">
              <div class="left"><span class="icon"><i class="{{ $child['icon'] }}"></i></span></div>
              <div class="text">
                <div class="title">{{ $child['title'] }}</div>
                <div class="sub-title">{{ $child['sub_title'] }}</div>
              </div>
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </div>
  @endforeach
@endsection


