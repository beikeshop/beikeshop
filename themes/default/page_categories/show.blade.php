@extends('layout.master')
@section('body-class', 'page-categories-home')
@section('title', $category->description->meta_title ?: system_setting('base.meta_title', 'BeikeShop开源好用的跨境电商系统 -
  BeikeShop官网') . ' - ' . $category->description->name)
@section('keywords', $category->description->meta_keywords ?: system_setting('base.meta_keyword'))
@section('description', $category->description->meta_description ?: system_setting('base.meta_description'))

@section('content')
  <div class="container">
    <x-shop-breadcrumb type="page_category" :value="$category['id']" />
    <div class="row">
      <div class="col-lg-9 col-12">
        <div class="card mb-4 shadow-sm">
          <div class="card-body">
            <h3>{{ $category->description->title }}</h3>
            <div>{{ $category->description->summary }}</div>
          </div>
        </div>

        <div class="card mb-4 shadow-sm">
          <div class="card-body">
            @if ($category_pages->count() > 0)
              @foreach ($category_pages as $page)
                <div>
                  <h5 class="card-title mb-2"><a class="text-black"
                      href="{{ type_route('page', $page) }}">{{ $page->description->title }}</a></h5>
                  <p class="fs-6 mb-3 text-secondary">{{ $page->created_at }}</p>
                  @if ($page->description->summary)
                    <p class="card-text mb-4">{{ $page->description->summary ?? '' }}</p>
                  @endif
                  <div class="text-danger"><a
                      href="{{ type_route('page', $page) }}">{{ __('shop/account.check_details') }}<i
                        class="bi bi-arrow-right-short"></i></a></div>
                </div>

                @if (!$loop->last)
                  <hr class="my-4">
                @endif
              @endforeach
            @else
              <x-shop-no-data />
            @endif

            {{ $category_pages->links('shared/pagination/bootstrap-4') }}
          </div>
        </div>
      </div>

      @if ($active_page_categories)
        <div class="col-lg-3 col-12">
          <div class="card mb-3 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="card-title">{{ __('product.category') }}</h5>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                @foreach ($active_page_categories as $category)
                  <li class="list-group-item p-0">
                    <a href="{{ type_route('page_category', $category) }}"
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
