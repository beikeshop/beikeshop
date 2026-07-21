<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  <div class="module-image-banner module-info   d-flex justify-content-center">
    <div class="{{ $content['module_size'] ?? 'container-fluid' }} d-flex justify-content-center">
      <a href="{{ $content['images'][0]['link']['link'] ?: 'javascript:void(0)' }}"><img src="{{ $content['images'][0]['image'] }}" alt="{{ $content['images'][0]['image_alt'] ?? '' }}" class="img-fluid seo-img"></a>
    </div>
  </div>
</section>