@extends('layout.master')
@section('body-class', 'page-list')

@section('content')
<div class="container">

  <x-shop-breadcrumb type="static" value="brands.index" /> 

  <div>
    <p class="fw-bold fs-3 d-flex justify-content-center mt-5 mb-4">{{ __('shop/brands.index') }}</p>
  </div>

  <ul class="d-flex justify-content-start mt-3 align-items-baseline mb-3 curser flex-wrap">
    @foreach ($brands as $brand)
      <li class="fs-6 border align-content-center text-center">
        <a href="brands#{{ $brand['0']['first'] }}" class="py-2 px-3">{{ $brand['0']['first'] }}</a>
      </li>
    @endforeach
  </ul>

  <ul>
    @foreach ($brands as $brand)
    <li class="d-flex border-top py-2">
      <p class="px-2 fs-5 mt-4 fw-bold py-5" id="{{ $brand['0']['first'] }}">{{ $brand['0']['first'] }}</p>
      <div class="container">
        <div class="row">
          @foreach ($brand as $item)
            <div class="text-center col-6 col-md-4 col-lg-2 mt-2">
              <a href="{{ shop_route('brands.show', [$item['id']]) }}">
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
