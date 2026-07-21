<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  <div class="module-info module-icons">
    @if ($content['title'])
    <div class="module-title">{{ $content['title'] }} <div class="wave-line"></div></div>
    @endif
    @if ($content['sub_title'])
    <div class="module-sub-title">{{ $content['sub_title'] }}</div>
    @endif
    <div class="{{ $content['module_size'] ?? 'container-fluid' }}">
      <div class="row g-3 g-lg-4">
        @foreach ($content['images'] as $image)
        <div class="col-4 col-lg">
          <a href="{{ $image['url'] ?: 'javascript:void(0)' }}" class="text-decoration-none">
            <div class="image-item d-flex justify-content-center mb-lg-3">
              <img src="{{ $image['image'] }}" class="img-fluid seo-img" alt="{{ $image['image_alt'] ?? ''}}">
            </div>
            @if ($image['text'])
            <p class="text-center text-dark mt-2 mb-0 title">{{ $image['text'] }}</p>
            @endif
            @if ($image['sub_text'])
            <p class="text-center text-secondary mt-2 mb-0 sub-title">{{ $image['sub_text'] }}</p>
            @endif
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>