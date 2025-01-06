@push('header')
  <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
@endpush

<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-info module-product mb-3 mb-md-5 swiper-style-plus">
    <div class="{{ $content['module_size'] ?? 'container-fluid' }} position-relative">
      <div class="module-title">{{ $content['title'] }} <div class="wave-line"></div></div>
        @if ($content['products'])
          <div class="row g-3 g-lg-4">
            @foreach ($content['products'] as $product)
            <div class="product-grid col-6 col-md-4 col-lg-3">
              @include('shared.product')
            </div>
            @endforeach
          </div>
        @elseif (!$content['products'] and $design)
          <div class="row g-3 g-lg-4">
            @for ($s = 0; $s < 4; $s++)
            <div class="col-6 col-md-4 col-lg-3">
              <div class="product-wrap">
                <div class="image"><a href="javascript:void(0)"><img src="{{ asset('catalog/placeholder.png') }}" class="img-fluid"></a></div>
                <div class="product-name">请配置商品</div>
                <div class="product-price">
                  <span class="price-new">66.66</span>
                  <span class="price-lod">99.99</span>
                </div>
              </div>
            </div>
          @endfor
        </div>
        @endif
    </div>
  </div>
</section>