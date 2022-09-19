<footer>
  @php
    $locale = locale();
  @endphp
  <div class="services-wrap">
    <div class="container">
      <div class="row">
        @foreach ($footer_content['services']['items'] as $item)
          <div class="col-lg-3 col-md-6 col-12">
            <div class="service-item my-1">
              <div class="icon"><img src="{{ image_resize($item['image'], 80, 80) }}" class="img-fluid"></div>
              <div class="text">
                <p class="title">{{ $item['title'][locale()] ?? '' }}</p>
                <p class="sub-title">{{ $item['sub_title'][locale()] ?? '' }}</p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
  <div class="container">
    <div class="footer-content">
      <div class="row">
        <div class="col-12 col-md-3">
          <div class="footer-content-left">
            <div class="logo"><a href="http://"><img
                  src="{{ image_origin($footer_content['content']['intro']['logo']) }}" class="img-fluid"></a></div>
            <div class="text tinymce-format-p">{!! $footer_content['content']['intro']['text'][$locale] ?? '' !!}</div>
          </div>
        </div>

        @for ($i = 1; $i <= 3; $i++)
          @php
            $link = $footer_content['content']['link' . $i];
          @endphp
          <div class="col-6 col-sm footer-content-link{{ $i }}">
            <h6 class="text-uppercase text-dark mb-3">{{ $link['title'][$locale] ?? '' }}</h6>
            <ul class="list-unstyled">
              @foreach ($link['links'] as $item)
                @if ($item['link'])
                <li class="lh-lg mb-2">
                  <a href="{{ $item['link'] }}" @if (isset($item['new_window']) && $item['new_window']) target="_blank" @endif>
                    {{ $item['type'] == 'custom' ? $item['text'][$locale] ?? '' : $item['text'] }}
                  </a>
                </li>
              @endif
              @endforeach
            </ul>
          </div>
        @endfor
        <div class="col-12 col-md-3 footer-content-contact">
          <h6 class="text-uppercase text-dark mb-3">{{ __('common.contact_us') }}</h6>
          <ul class="list-unstyled">
            @if ($footer_content['content']['contact']['email'])
              <li class="lh-lg mb-2"><i class="bi bi-envelope-fill"></i> {{ $footer_content['content']['contact']['email'] }}</li>
            @endif
            @if ($footer_content['content']['contact']['telephone'])
              <li class="lh-lg mb-2"><i class="bi bi-telephone-fill"></i> {{ $footer_content['content']['contact']['telephone'] }}</li>
            @endif
            @if ($footer_content['content']['contact']['address'][$locale] ?? '')
              <li class="lh-lg mb-2"><i class="bi bi-geo-alt-fill"></i> {{ $footer_content['content']['contact']['address'][$locale] ?? '' }}</li>
            @endif
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <div class="row align-items-center">
        <div class="col">
          {!! $footer_content['bottom']['copyright'][$locale] ?? '' !!}
        </div>
        @if (isset($footer_content['bottom']['image']))
          <div class="col-auto">
            <img src="{{ image_origin($footer_content['bottom']['image']) }}" class="img-fluid">
          </div>
        @endif
      </div>
    </div>
  </div>
</footer>
