@push('header')
<script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
@endpush

<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-info module-pages mb-3 mb-md-4">
    <div class="container position-relative">
      <div class="module-title">{{ $content['title'] }}</div>
      @if ($content['items'])
        <div class="row">
          @foreach ($content['items'] as $item)
          <div class="col-6 col-md-4 col-lg-3">
            <div class="pages-wrap">
              <div class="image"><a href="{{ (isset($item['url']) ? $item['url'] : shop_route('pages.show', [$item['id']])) }}"><img src="{{ $item['image'] }}" class="img-fluid"></a>
              </div>
              <div class="page-info">
                <div class="pages-title"><a href="{{ shop_route('pages.show', [$item['id']]) }}">{{ $item['title'] ?? '' }}</a></div>
                <div class="pages-summary">{{ $item['summary'] ?? '' }}</div>
                <div class="pages-view"><a href="{{ shop_route('pages.show', [$item['id']]) }}">{{ __('shop/account.check_details') }}<i class="bi bi-arrow-right-short"></i></a></div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        @if (count($content['items']) > 4)
        <div class="d-flex justify-content-center mt-4">
          <a class="btn btn-outline-secondary btn-lg" href="{{ shop_route('page_categories.home') }}">{{ __('common.show_all') }}</a>
        </div>
        @endif
      @elseif (!$content['items'] and $design)
        <div class="row">
          @for ($s = 0; $s < 4; $s++)
          <div class="col-6 col-md-4 col-lg-3">
            <div class="pages-wrap">
              <div class="image"><a href="javascript:void(0)"><img src="{{ asset('catalog/placeholder.png') }}" class="img-fluid"></a></div>
              <div class="pages-name">请配置文章</div>
            </div>
          </div>
          @endfor
        </div>
      </div>
      @endif
    </div>
  </div>
</section>
