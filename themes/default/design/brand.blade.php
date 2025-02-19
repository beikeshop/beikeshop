<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-info module-brand mb-3 mb-md-5">
    <div class="module-title">{{ $content['title'] }} <div class="wave-line"></div></div>
    <div class="{{ $content['module_size'] ?? 'container-fluid' }}">
      <div class="row g-3 g-lg-4">
        @foreach ($content['brands'] as $brand)
        <div class="col-6 col-md-4 col-lg-3">
          <a href="{{ $brand['url'] }}" class="text-decoration-none">
            <div class="brand-item">
              <img src="{{ $brand['logo'] ?? asset('image/default/banner-1.png') }}" alt="{{ $brand['name'] }}" class="img-fluid seo-img">
            </div>
            <p class="text-center text-dark mb-0">{{ $brand['name'] }}</p>
          </a>
        </div>
        @endforeach
      </div>
    </div>
    @if ($content['brands'])
    <div class="d-flex justify-content-center mt-4">
      <a class="btn btn-outline-secondary btn-lg" href="{{ shop_route('brands.index') }}">{{ __('common.show_all') }}</a>
    </div>
    @endif
  </div>
</section>