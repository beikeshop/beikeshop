<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  <div class="module-info  ">
    <div class="img-text-banner-wrap {{ $content['module_size'] ?? 'container-fluid' }}">
      <div class="row {{ $content['image_position'] == 'left' ? 'flex-row-reverse' : '' }} mx-0">
        <div class="col-12 col-lg-6 px-0">
          <div class="text-wrap {{ $content['text_position'] == 'left' ? 'align-items-start text-start' : ($content['text_position'] == 'center' ? 'align-items-center text-center' : 'align-items-end text-end') }}" style="background-color: {{ $content['bg_color'] }}; color: {{ $content['text_color'] ?? '#222' }}">
            <div style="max-width: {{ $content['text_max_width'] ?? '1900' }}px">
              <div class="fs-2 fw-bold title">{{ $content['title'] }}</div>
              @if ($content['sub_title'])
              <div class="fs-4 sub-title mb-4">{{ $content['sub_title'] }}</div>
              @endif
              <p class="description">{{ $content['description'] }}</p>
              <a href="{{ $content['link'] }}" class="btn btn-lg" style="background-color: {{ $content['btn_bg'] ?? '#fd560f' }}; color: {{ $content['btn_color'] ?? '#fff' }}">{{ __('common.view_more') }}</a>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-6 px-0">
          <div class="img-wrap">
            <img src="{{ $content['image'] }}" class="img-fluid seo-img" alt="{{ $content['image_alt'] ?? '' }}">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>