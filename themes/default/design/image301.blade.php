<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  <div class="module-image-banner banner-magnify-hover module-info  ">
    <div class="{{ $content['module_size'] ?? 'container' }}">
      <div class="design-image-301">
        @foreach ($content['images'] as $item)
        <a href="{{ $item['link']['link'] ?: 'javascript:void(0)' }}"><img src="{{ $item['image'] }}" alt="{{ $item['image_alt'] ?? '' }}" class="img-fluid seo-img"></a>
        @endforeach
      </div>
    </div>
  </div>
</section>