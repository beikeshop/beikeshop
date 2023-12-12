<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-image-402 banner-magnify-hover module-info mb-3 mb-md-5">
    <div class="container">
      @if ($content['title'][locale()] ?? false)
      <div class="module-title">{{ $content['title'][locale()] }}</div>
      @endif
      @if ($content['sub_title'][locale()] ?? false)
      <div class="image-402-sub-title mt-n3">{{ $content['sub_title'][locale()] }}</div>
      @endif
      <div class="module-image-info d-grid grid-4">
        <div class="image-402-1">
          <a class="image-wrap" href="{{ $content['images'][0]['link']['link'] ?? '' }}">
            <img src="{{ $content['images'][0]['image'] }}" class="img-fluid">
            @if ($content['images'][0]['title'][locale()] ?? false)
              <div class="img-name"><span>{{ $content['images'][0]['title'][locale()] }}</span></div>
            @endif
          </a>
        </div>
        <div class="image-402-2">
          <a class="image-wrap" href="{{ $content['images'][1]['link']['link'] ?? '' }}">
            <img src="{{ $content['images'][1]['image'] }}" class="img-fluid">
            @if ($content['images'][1]['title'][locale()] ?? false)
              <div class="img-name"><span>{{ $content['images'][1]['title'][locale()] }}</span></div>
            @endif
          </a>
        </div>
        <div class="image-402-3">
          <a class="image-wrap" href="{{ $content['images'][2]['link']['link'] ?? '' }}">
            <img src="{{ $content['images'][2]['image'] }}" class="img-fluid">
            @if ($content['images'][2]['title'][locale()] ?? false)
              <div class="img-name"><span>{{ $content['images'][2]['title'][locale()] }}</span></div>
            @endif
          </a>
        </div>
        <div class="image-402-4">
          <a class="image-wrap" href="{{ $content['images'][3]['link']['link'] ?? '' }}">
            <img src="{{ $content['images'][3]['image'] }}" class="img-fluid">
            @if ($content['images'][3]['title'][locale()] ?? false)
              <div class="img-name"><span>{{ $content['images'][3]['title'][locale()] }}</span></div>
            @endif
          </a>
        </div>
      </div>
    </div>
  </div>
</section>