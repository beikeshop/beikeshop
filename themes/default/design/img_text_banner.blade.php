<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-info mb-3 mb-md-5">
    <div class="img-text-banner-wrap container-fluid">
      <div class="row p-0">
        <div class="col-12 col-lg-6 p-0">
          <div class="text-wrap" style="background-color: {{ $content['bg_color'] }}">
            <h2 class="title">{{ $content['title'] }}</h2>
            <p class="description">{{ $content['description'] }}</p>
            <a href="{{ $content['link'] }}" class="btn btn-primary">{{ __('common.view_more') }}</a>
          </div>
        </div>
        <div class="col-12 col-lg-6 p-0">
          <div class="img-wrap">
            <img src="{{ $content['image'] }}" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



