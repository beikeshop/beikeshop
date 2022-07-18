@push('header')
  <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
@endpush

<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-dasuybkdas">
  @if ($design)
  <div class="module-edit">
    <div class="edit-wrap">
      <div class=""><i class="bi bi-chevron-down"></i></div>
      <div class=""><i class="bi bi-chevron-up"></i></div>
      <div class="delete"><i class="bi bi-x-lg"></i></div>
      <div class="edit"><i class="bi bi-pencil-square"></i></div>
    </div>
  </div>
  @endif
  <div class="module-info">
    <div class="swiper module-swiper-dasuybkdas module-slideshow">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <a href=""><img src="{{ asset('image/default/banner.png') }}" class="img-fluid"></a>
        </div>
        <div class="swiper-slide">
          <a href=""><img src="{{ asset('image/default/banner.png') }}" class="img-fluid"></a>
        </div>
      </div>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </div>
</section>

@push('add-scripts')
  <script>
    new Swiper ('.module-swiper-dasuybkdas', {
      loop: true, // 循环模式选项
      autoplay: true,
      pauseOnMouseEnter: true,
      clickable :true,

      // 如果需要分页器
      pagination: {
        el: '.swiper-pagination',
      },

      // 如果需要前进后退按钮
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    })
    </script>
  @endpush


