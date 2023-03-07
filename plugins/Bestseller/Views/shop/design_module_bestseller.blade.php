<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')
  <div class="module-info module-product mb-3 mb-md-5 swiper-style-plus">
    <div class="container position-relative">
      <div class="module-title">{{ $content['title'] }}</div>
      @if ($content['products'])
        <div class="swiper module-product-{{ $module_id }} module-slideshow">
          <div class="swiper-wrapper">
            @foreach ($content['products'] as $product)
            <div class="swiper-slide">
              @include('shared.product')
            </div>
            @endforeach
          </div>
        </div>
        <div class="swiper-pagination rectangle module-product-{{ $module_id }}-pagination"></div>
        <div class="swiper-button-prev product-prev"></div>
        <div class="swiper-button-next product-next"></div>
      @elseif (!$content['products'] and $design)
      <div class="row">
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

  <script>
    new Swiper ('.module-product-{{ $module_id }}', {
      loop: 1,
      watchSlidesProgress: true,
      breakpoints:{
        320: {
          slidesPerView: 2,
          spaceBetween: 10,
        },
        768: {
          slidesPerView: 4,
          spaceBetween: 30,
        },
      },
      spaceBetween: 30,
      // 如果需要分页器
      pagination: {
        el: '.module-product-{{ $module_id }}-pagination',
        clickable: true,
      },

      // 如果需要前进后退按钮
      navigation: {
        nextEl: '.product-next',
        prevEl: '.product-prev',
      },
    })
  </script>
</section>
