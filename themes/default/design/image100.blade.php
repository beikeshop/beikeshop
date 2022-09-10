<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @if ($design)
  <div class="module-edit">
    <div class="edit-wrap">
      <div class="down"><i class="bi bi-chevron-down"></i></div>
      <div class="up"><i class="bi bi-chevron-up"></i></div>
      <div class="delete"><i class="bi bi-x-lg"></i></div>
      <div class="edit"><i class="bi bi-pencil-square"></i></div>
    </div>
  </div>
  @endif

  <div class="module-image-plus module-info {{ !$content['full'] ? 'container' : '' }} mb-3 mb-md-5 d-flex justify-content-center">
    <div class="container{{ $content['full'] ? '-fluid' : '' }} d-flex justify-content-center">
      <a class="col-12" href="{{ $content['images'][0]['link']['link'] ?? '' }}"><img src="{{ $content['images'][0]['image'] }}" class="img-fluid"></a>
    </div>
  </div>
</section>