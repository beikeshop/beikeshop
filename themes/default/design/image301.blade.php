<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-image-banner banner-magnify-hover module-info mb-3 mb-md-5">
    <div class="{{ $content['module_size'] ?? 'container' }}">
      <div class="design-image-301">
        @foreach ($content['images'] as $item)
        <a href="{{ $item['link']['link'] ?: 'javascript:void(0)' }}"><img src="{{ $item['image'] }}" class="img-fluid"></a>
        @endforeach
      </div>
    </div>
  </div>
</section>