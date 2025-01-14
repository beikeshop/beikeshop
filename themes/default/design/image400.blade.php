<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="banner-magnify-hover module-info mb-3 mb-md-5">
    <div class="{{ $content['module_size'] ?? 'container-fluid' }}">
      <div class="row g-3 g-lg-4">
        @foreach ($content['images'] as $item)
        <div class="col-6 col-md-3">
          <a class="image-wrap d-flex justify-content-center" href="{{ $item['link']['link'] ?: 'javascript:void(0)' }}">
            <img src="{{ $item['image'] }}" class="img-fluid">
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>