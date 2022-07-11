@extends('layout.master')
@section('body-class', 'page-home')
@section('content')


{{--@foreach($renders as $render)--}}
{{--  <x-dynamic-component :component="$render"/>--}}
{{--@endforeach--}}

{!! $html !!}

<script>
  $(function() {
    $(document).on('click', '.module-edit .edit', function(event) {
      window.parent.postMessage({index: 0}, '*')
    });
  });
</script>
{{-- <section class="module-wrap mb-5"><img src="{{ asset('image/default/banner.png') }}" class="img-fluid"></section> --}}
{{-- @foreach ($categories as $category)
  <a href="{{ shop_route('categories.show', $category) }}">{{ $category->description->name }}</a>
@endforeach --}}
{{-- <section class="module-image-plus mb-5">
  <div class="container">
    <div class="row">
      <div class="col-6"><img src="{{ asset('image/default/image_plus_1.png') }}" class="img-fluid"></div>
      <div class="col-6">
        <div class="module-image-plus-top">
          <a href=""><img src="{{ asset('image/default/image_plus_2.png') }}" class="img-fluid"></a>
          <a href="" class="right"><img src="{{ asset('image/default/image_plus_3.png') }}" class="img-fluid"></a>
        </div>
        <div class="module-image-plus-bottom"><a href=""><img src="{{ asset('image/default/image_plus_4.png') }}" class="img-fluid"></a></div>
      </div>
    </div>
  </div>
</section>

<section class="module-tab-product mb-4">
  <div class="module-title">推荐商品模块</div>
  <div class="container">
    <div class="nav justify-content-center mb-3">
      @foreach ($category_products as $key => $category)
      <a class="nav-link {{ $loop->first ? 'active' : '' }}" href="#tab-product-{{ $loop->index }}" data-bs-toggle="tab">{{ $key }}</a>
      @endforeach
    </div>
    <div class="tab-content">
      @foreach ($category_products as $products)
      <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}" id="tab-product-{{ $loop->index }}">
        <div class="row">
          @foreach ($products as $product)
          <div class="col-6 col-md-4 col-lg-3">
            @include('shared.product')
          </div>
          @endforeach
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="module-image-banner mb-5">
  <img src="{{ asset('image/default/banner-2.png') }}" class="img-fluid">
</section>

<section class="module-brand mb-5">
  <div class="module-title">推荐品牌模块</div>
  <div class="container">
    <div class="row">
      @for ($i = 0; $i < 8; $i++)
      <div class="col-6 col-md-4 col-lg-3">
        <div class="brand-item"><img src="{{ asset('image/default/banner-1.png') }}" class="img-fluid"></div>
      </div>
      @endfor
    </div>
  </div>
</section> --}}
@endsection
