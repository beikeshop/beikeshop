<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-image-banner banner-magnify-hover module-info mb-3 mb-md-5">
    <div class="container">
      <div class="row">
        @foreach ($content['images'] as $item)
        <div class="col-12 col-md-4"><a class="image-wrap" href="{{ $item['link']['link'] ?: 'javascript:void(0)' }}"><img src="{{ $item['image'] }}" class="img-fluid"></a></div>
        @endforeach
      </div>
    </div>
  </div>
</section>