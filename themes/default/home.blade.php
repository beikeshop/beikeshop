@extends('layout.master')

@section('content')
<section class="module-wrap mb-5"><img src="{{ asset('image/default/banner.png') }}" class="img-fluid"></section>
{{-- @foreach ($categories as $category)
  <a href="{{ shop_route('categories.show', $category) }}">{{ $category->description->name }}</a>
@endforeach --}}
<section class="module-image-plus mb-5">
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
      <a class="nav-link active" href="#tab-product-1" data-bs-toggle="tab">Women</a>
      <a class="nav-link" href="#tab-product-2" data-bs-toggle="tab">Men</a>
      <a class="nav-link" href="#tab-product-3" data-bs-toggle="tab">Kids</a>
    </div>
    <div class="tab-content">
      <div class="tab-pane fade show active" id="tab-product-1">
        <div class="row">
          @for ($i = 0; $i < 10; $i++)
          <div class="col-6 col-md-4 col-lg-3">
            @include('layout.product')
          </div>
          @endfor
        </div>
      </div>
      <div class="tab-pane fade" id="tab-product-2">
        22222
      </div>
      <div class="tab-pane fade" id="tab-product-3">
        33333
      </div>
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
</section>
@endsection
