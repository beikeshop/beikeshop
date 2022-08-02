@extends('layout.master')
@section('body-class', 'page-list')

@section('content')
<div class="container">
  {{-- {{ dd($brands) }} --}}
  <div>
    <p class="fw-bold fs-3 d-flex justify-content-center mt-5 mb-4">品牌列表</p>
  </div>

  <ul class="d-flex justify-content-between mt-3 align-items-baseline mb-3 curser">
    @foreach ($brands as $brand)
      <li class="fs-6 border flex-fill align-content-center text-center">
        <a href="brands#{{ $brand['0']['first'] }}" class="py-2">{{ $brand['0']['first'] }}</a>
      </li>
    @endforeach
  </ul>

  <ul>
    @foreach ($brands as $brand)
    <li class="d-flex justity-content-between align-items-center border-top py-1">
      <p class="ps-2 fs-5 mt-4 fw-bold py-5" id="{{ $brand['0']['first'] }}">{{ $brand['0']['first'] }}</p>
      <div class="flex-wrap d-flex justity-content-between align-items-center">
        @foreach ($brand as $item)
          <div class="text-center">
            <a href="{{ shop_route('brands.show', [$item['id']]) }}">
              <img src="{{ $item['logo'] }}" class="img-fluid mx-5" alt="{{ $item['name'] }}">
              <p class="mb-0 mt-1">{{ $item['name'] }}</p>
            </a>
          </div>
        @endforeach
      </div>
    </li>
    @endforeach
  </ul>
</div>

@endsection
