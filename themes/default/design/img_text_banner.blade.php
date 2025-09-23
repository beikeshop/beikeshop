<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-info mb-3 mb-md-5">
    <div class="img-text-banner-wrap {{ $content['module_size'] ?? 'container-fluid' }}">
      <div class="row">
        <div class="col-12 col-lg-6 pe-lg-0">
          <div class="text-wrap" style="background-color: {{ $content['bg_color'] }}; color: {{ $content['text_color'] ?? '#222' }}">
            <div class="fs-2 fw-bold title">{{ $content['title'] }}</div>
            <p class="description">{{ $content['description'] }}</p>
            <a href="{{ $content['link'] }}" class="btn" style="background-color: {{ $content['btn_bg'] ?? '#fd560f' }}; color: {{ $content['btn_color'] ?? '#fff' }}">{{ __('common.view_more') }}</a>
          </div>
        </div>
        <div class="col-12 col-lg-6 ps-lg-0">
          <div class="img-wrap">
            <img src="{{ $content['image'] }}" class="img-fluid seo-img" alt="{{ $content['image_alt'] ?? '' }}">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>