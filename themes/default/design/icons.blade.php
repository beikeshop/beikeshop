<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-info mb-3 mb-md-5">
    @if ($content['title'])
    <div class="module-title">{{ $content['title'] }}</div>
    @endif
    <div class="container">
      <div class="row">
        @foreach ($content['images'] as $image)
        <div class="col-6 col-md-4 col-lg-2">
          <a href="{{ $image['url'] ?: 'javascript:void(0)' }}" class="text-decoration-none">
            <div class="image-item d-flex justify-content-center mb-3">
              <img src="{{ $image['image'] }}" class="img-fluid">
            </div>
            @if ($image['text'])
            <p class="text-center text-dark mb-2 mt-2 fs-5">{{ $image['text'] }}</p>
            @endif
            @if ($image['sub_text'])
            <p class="text-center text-secondary my-2">{{ $image['sub_text'] }}</p>
            @endif
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>