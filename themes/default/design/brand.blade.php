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

  <div class="module-info module-brand mb-3 mb-md-5">
    {{-- {{ dd($content) }} --}}
    <div class="module-title">{{ $content['title'] }}</div>
    <div class="container">
      <div class="row">
        @foreach ($content['brands'] as $brand)
        <div class="col-6 col-md-4 col-lg-3">
          <a href="{{ $brand['url'] }}" class="text-decoration-none">
            <div class="brand-item">
              <img src="{{ $brand['logo'] ?? asset('image/default/banner-1.png') }}" class="img-fluid">
            </div>
            <p class="text-center text-dark mb-4">{{ $brand['name'] }}</p>
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>