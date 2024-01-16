<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-image-banner module-info {{ !$content['full'] ? 'container' : '' }} mb-3 mb-md-5 d-flex justify-content-center">
    <div class="container{{ $content['full'] ? '-fluid' : '' }} d-flex justify-content-center">
      <a href="{{ $content['images'][0]['link']['link'] ?: 'javascript:void(0)' }}"><img src="{{ $content['images'][0]['image'] }}" class="img-fluid"></a>
    </div>
  </div>
</section>