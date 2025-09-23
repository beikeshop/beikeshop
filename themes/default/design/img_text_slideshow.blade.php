@push('header')
  <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
@endpush

<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-info mb-3 mb-md-5">
    <div class="{{ $content['module_size'] ?? 'w-100' }}">
      <div class="swiper module-swiper-img-text-{{ $module_id }} module-img-text-slideshow">
        <div class="swiper-wrapper">
          @foreach($content['images'] as $image)
            <div class="swiper-slide">
              <div class="image-wrap" style="background-image: url({{ $image['image'] }})">
                <div class="container content-wrap {{ $image['text_position'] }}">
                  <div class="text-wrap" data-swiper-parallax-y="-100" data-swiper-parallax-duration="1000" data-swiper-parallax-opacity="0.5" >
                    @if ($image['sub_title'])
                      <div class="sub-title">{{ $image['sub_title'] }}</div>
                    @endif
                    @if ($image['title'])
                      <h2 class="title">{{ $image['title'] }}</h2>
                    @endif
                    @if ($image['description'])
                      <p class="description">{{ $image['description'] }}</p>
                    @endif
                    @if ($image['link']['link'])
                      <a href="{{ $image['link']['link'] ?: 'javascript:void(0)' }}" class="btn">{{ __('shop/account.check_details') }}</a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <div class="swiper-pagination slideshow-pagination-{{ $module_id }}"></div>
      </div>

      @if ($content['scroll_text']['text'])
        <div class="module-swiper-img-scroll-text" style="
          background-color: {{ $content['scroll_text']['bg'] }};
          color: {{ $content['scroll_text']['color'] }};
          font-size: {{ $content['scroll_text']['font_size'] }}px;
          padding: {{ $content['scroll_text']['padding'] }}px 0;
          ">
          <div class="scroll-info">
            <span class="scroll-text">{{ $content['scroll_text']['text'] }}</span>
          </div>
        </div>
      @endif
    </div>
  </div>

  <script>
    var moduleSwiperImgText_{{ $module_id }} = new Swiper ('.module-swiper-img-text-{{ $module_id }}', {
      loop: true,
      parallax : true,
      pauseOnMouseEnter: true,
      clickable :true,
      effect: 'fade',

      pagination: {
        el: '.slideshow-pagination-{{ $module_id }}',
        clickable: true
      },

      autoplay: {
        delay: 3000,
        disableOnInteraction: false
      },

      on: {
        init: function () {
          $('.slideshow-pagination-{{ $module_id }} .swiper-pagination-bullet').append('<span></span>')
        },
        autoplayTimeLeft(s, time, progress) {
          $('.slideshow-pagination-{{ $module_id }} .swiper-pagination-bullet-active span').css('width', (1 - progress) * 100 + '%')
        },
        slideChange: function () {
          $('.slideshow-pagination-{{ $module_id }} .swiper-pagination-bullet span').css('width', '0')
        },
      }
    })

    $('.module-img-text-slideshow').hover(function() {
      moduleSwiperImgText_{{ $module_id }}.autoplay.pause();
    }, function() {
      moduleSwiperImgText_{{ $module_id }}.autoplay.resume();
    });

    $(function () {
      scrollTextFun()

      function scrollTextFun() {
        var $module = $('.module-swiper-img-text-{{ $module_id }}').next('.module-swiper-img-scroll-text');
        if (!$module.length) {
          return;
        }

        var $scrollText = $module.find('.scroll-text');
        var scrollText = $scrollText.text();
        var scrollTextWidth = $scrollText.width();
        var scrollInfoWidth = $module.width();

        // 滚动速度（像素/秒）
        var speed = 100; // 可调整滚动速度
        var duration = scrollTextWidth / speed; // 动画持续时间，单位：秒

        // 计算需要重复的次数，确保内容填满容器
        var scrollCount = Math.ceil(scrollInfoWidth / scrollTextWidth) + 1;
        var scrollTextHtml = '';

        // 拼接滚动内容
        for (var i = 0; i < scrollCount; i++) {
          scrollTextHtml +=
            '<span class="scroll-text" style="animation-duration: ' + duration + 's;">' +
            scrollText +
            '</span>';
        }

        // 更新滚动区域内容
        $module.find('.scroll-info').html(scrollTextHtml);
      }
    })
  </script>
</section>



