@extends('layout.master')
@section('body-class', 'page-pages')
@section('title', $page->description->meta_title ?: $page->description->title)
@section('keywords', $page->description->meta_keywords)
@section('description', $page->description->meta_description)

@push('header')
  <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/scrolltofixed/jquery-scrolltofixed-min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
@endpush

@section('content')
  <x-shop-breadcrumb type="page" :value="$page['id']" />
  <div class="container">
    <div class="row">
      <div class="{{ $page->category ? "col-lg-9 col-12" : 'col-12' }}">
        <div class="card shadow-sm page-content">
          <div class="card-body h-min-600 p-lg-4">
            <h2 class="mb-3">{{ $page->description->title }}</h2>

            @if ($page->category)
              <div class="text-secondary opacity-75 mb-4">
                <span class="me-3"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> {{ __('page_category.author') }}: {{ $page->author }}</span>
                <span class="me-3"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> {{ __('page_category.created_at') }}: {{ $page->created_at }}</span>
                <span><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> {{ __('page_category.views') }}: {{ $page->views }}</span>
              </div>
            @endif
            {!! $page_format['content'] !!}


            @if ($products)
              <div class="relations-wrap mt-5">
                <div class="container position-relative">
                  <div class="title text-center fs-4 mb-4">{{ __('admin/product.product_relations') }}</div>
                  <div class="product swiper-style-plus">
                    <div class="swiper relations-swiper">
                      <div class="swiper-wrapper">
                        @foreach ($products as $item)
                        <div class="swiper-slide">
                          @include('shared.product', ['product' => $item])
                        </div>
                        @endforeach
                      </div>
                    </div>
                    <div class="swiper-pagination relations-pagination"></div>
                    <div class="swiper-button-prev relations-swiper-prev"></div>
                    <div class="swiper-button-next relations-swiper-next"></div>
                  </div>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>

      @if ($page->category)
        <div class="col-lg-3 col-12">
          <div class="card mb-3 shadow-sm h-min-300 x-fixed-top">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="card-title">{{ __('product.category') }}</h5>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                @foreach ($active_page_categories as $category)
                  <li class="list-group-item p-0">
                    <a href="{{ shop_route('page_categories.show', [$category->id]) }}"
                      class="p-2 list-group-item-action nav-link">{{ $category->description->title }}</a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>
@endsection

@push('add-scripts')
<script>
  if ($('.relations-swiper').length) {
    var relationsSwiper = new Swiper ('.relations-swiper', {
      watchSlidesProgress: true,
      breakpoints:{
        320: {
          slidesPerView: 2,
          slidesPerGroup: 2,
          spaceBetween: 10,
        },
        768: {
          slidesPerView: 4,
          slidesPerGroup: 4,
          spaceBetween: 30,
        },
      },
      spaceBetween: 30,
      // 如果需要前进后退按钮
      navigation: {
        nextEl: '.relations-swiper-next',
        prevEl: '.relations-swiper-prev',
      },

      // 如果需要分页器
      pagination: {
        el: '.relations-pagination',
        clickable: true,
      },
    })
  }
</script>
@endpush