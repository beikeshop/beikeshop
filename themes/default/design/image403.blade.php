<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-image-403 banner-magnify-hover module-info mb-3 mb-md-5">
    <div class="{{ $content['module_size'] ?? 'container-fluid' }}">
      @if ($content['title'])
      <div class="module-title">{{ $content['title'] }} <div class="wave-line"></div></div>
      @endif
      @if ($content['sub_title'])
      <div class="module-sub-title">{{ $content['sub_title'] }}</div>
      @endif

      <div class="row g-3 g-lg-4">
        @foreach ($content['images'] as $item)
        <div class="col-6 col-md-3">
          <a class="image-wrap" href="{{ $item['link']['link'] ?: 'javascript:void(0)' }}"><img src="{{ $item['image'] }}" alt="{{ $item['image_alt'] ?? ''}}" class="img-fluid seo-img">
            @if ($item['title'])
            <span class="title">{{ $item['title'] }}</span>
            @endif
            @if ($item['sub_title'])
            <span class="sub-title">{{ $item['sub_title'] }}</span>
            @endif
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>