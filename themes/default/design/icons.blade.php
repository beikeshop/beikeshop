<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-info mb-3 mb-md-5">
    <div class="module-title">{{ $content['title'] }}</div>
    <div class="container">
      <div class="row">
        @foreach ($content['images'] as $image)
        <div class="col-6 col-md-4 col-lg-2">
          <a href="{{ $image['link'] }}" class="text-decoration-none">
            <div class="image-item">
              <img src="{{ $image['image'] }}" class="img-fluid">
            </div>
            <p class="text-center text-dark mb-4 mt-2">{{ $image['text'] }}</p>
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>