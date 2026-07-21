<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  <div class="module-info banner-magnify-hover module-img-text-banner-multiple ">
    @if ($content['title'])
    <div class="module-title">{{ $content['title'] }} <div class="wave-line"></div></div>
    @endif
    @if ($content['sub_title'])
    <div class="module-sub-title">{{ $content['sub_title'] }}</div>
    @endif
    <div class="{{ $content['module_size'] ?? 'container-fluid' }}">
      <div class="row g-2 g-lg-4">
        @foreach ($content['images'] as $image)
        <div class="col-lg {{ count($content['images']) % 2 == 0 ? 'col-6' : 'col' }}">
          <a href="{{ $image['url'] ?: 'javascript:void(0)' }}" class="text-decoration-none image-wrap">
            <div class="image-item d-flex justify-content-center">
              <img src="{{ $image['image'] }}" class="img-fluid seo-img" alt="{{ $image['image_alt'] ?? ''}}">
            </div>
            <div class="text-wrap">
              @if ($image['sub_text'])
              <p class="text-center text-sub-title">{{ $image['sub_text'] }}</p>
              @endif
              @if ($image['text'])
              <p class="text-center text-title">{{ $image['text'] }}</p>
              @endif
              @if ($image['url'] ?? false)
                <button class="btn">{{ __('common.view_details') }}</button>
              @endif
            </div>
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>