<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-image-plus banner-magnify-hover module-info mb-3 mb-md-5">
    <div class="{{ $content['module_size'] ?? 'container-fluid' }}">
      <div class="row g-3 g-lg-4">
        <div class="col-md-6 col-12 mb-3 mb-md-0">
          <a class="image-wrap" href="{{ $content['images'][0]['link']['link'] ?? '' }}"><img src="{{ $content['images'][0]['image'] }}" alt="{{ $content['images'][0]['image_alt'] }}" class="img-fluid seo-img"></a>
        </div>
        <div class="col-md-6 col-12">
          <div class="module-image-plus-top">
            <a class="image-wrap" href="{{ $content['images'][1]['link']['link'] ?? '' }}"><img src="{{ $content['images'][1]['image'] }}" alt="{{ $content['images'][1]['image_alt'] }}" class="img-fluid seo-img"></a>
            <a href="{{ $content['images'][2]['link']['link'] ?? '' }}" class="right image-wrap"><img src="{{ $content['images'][2]['image'] }}" alt="{{ $content['images'][2]['image_alt'] }}" class="img-fluid seo-img"></a>
          </div>
          <div class="module-image-plus-bottom"><a class="image-wrap" href="{{ $content['images'][3]['link']['link'] ?? '' }}"><img src="{{ $content['images'][3]['image'] }}" alt="{{ $content['images'][3]['image_alt'] }}" class="img-fluid seo-img"></a></div>
        </div>
      </div>
    </div>
  </div>
</section>