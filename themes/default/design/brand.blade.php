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

  <div class="module-info module-brand mb-5">
    <div class="module-title">{{ $content['title'] }}</div>
    <div class="container">
      <div class="row">
        @for ($i = 0; $i < 8; $i++)
        <div class="col-6 col-md-4 col-lg-3">
          <div class="brand-item"><img src="{{ asset('image/default/banner-1.png') }}" class="img-fluid"></div>
        </div>
        @endfor
      </div>
    </div>
  </div>
</section>