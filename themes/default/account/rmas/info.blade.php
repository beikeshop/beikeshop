@extends('layout.master')

@section('body-class', 'page-account-rmas')

@push('header')
  {{-- <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script> --}}
@endpush

@section('content')
  <div class="container" id="address-app">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>

    <div class="row">
      <x-shop-sidebar/>

      <div class="col-12 col-md-9">
        <div class="card h-min-600">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">售后详情</h5>
          </div>
          <div class="card-body h-600">

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('add-scripts')

@endpush
