<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @if ($design)
  <div class="module-edit">
    <div class="edit-wrap">
      <div class=""><i class="bi bi-chevron-down"></i></div>
      <div class=""><i class="bi bi-chevron-up"></i></div>
      <div class="delete"><i class="bi bi-x-lg"></i></div>
      <div class="edit"><i class="bi bi-pencil-square"></i></div>
    </div>
  </div>
  @endif

  <div class="module-image-plus module-info mb-5">
    <div class="container{{ $content['full'] ? '-fluid' : '' }}">
      <a class="col-12"><img src="{{ $content['images'][0]['image'] }}" class="img-fluid"></a>
    </div>
  </div>
</section>