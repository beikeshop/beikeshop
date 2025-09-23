@extends('layout.master')

@section('body-class', 'page-maintenance')

@push('header')
@endpush

@section('content')
<div class="container" id="app">
  <div class="card mt-5 w-max-500 m-auto">
    <div class="">
      <div class="card-header mt-3 mb-4 px-lg-4 text-center">
        <div class="mb-5"><img src="{{ asset('image/maintenance-icon.svg') }}" alt="{{ __('maintenance.maintenance_text') }}" class="img-fluid"></div>
        <h4 class="fw-bold">{{ __('maintenance.maintenance_text') }}</h4>
      </div>
    </div>
  </div>
</div>
@endsection

@push('footer')
@endpush
