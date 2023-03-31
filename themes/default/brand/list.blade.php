@extends('layout.master')
@section('body-class', 'page-brands')

@section('content')
<x-shop-breadcrumb type="static" value="brands.index" />

<div class="container">
  <p class="fw-bold fs-3 d-flex justify-content-center mb-4">{{ __('shop/brands.index') }}</p>

  <ul class="list-group list-group-horizontal mb-5 curser-list">
    @foreach ($brands as $brand)
      <li class="list-group-item p-0 flex-grow-1">
        <a href="brands#{{ $brand['0']['first'] }}" class="py-2 px-3 text-center fw-bold d-block">{{ $brand['0']['first'] }}</a>
      </li>
    @endforeach
  </ul>

  <ul class="brand-list ps-0">
    @foreach ($brands as $brand)
    <li class="d-flex border-top py-3">
      <p class="px-2 fs-5 mt-4 fw-bold py-5" id="{{ $brand['0']['first'] }}">{{ $brand['0']['first'] }}</p>
      <div class="container">
        <div class="row">
          @foreach ($brand as $item)
            <div class="text-center col-6 col-md-4 col-lg-2 mt-2">
              <a href="{{ type_route('brand', $item['id']) }}">
                <div class="brand-item">
                  <img src="{{ $item['logo'] }}" class="img-fluid" alt="{{ $item['name'] }}">
                </div>
                <p class="mb-0 mt-1 ">{{ $item['name'] }}</p>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </li>
    @endforeach
  </ul>
</div>

@endsection
