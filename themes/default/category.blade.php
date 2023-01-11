@extends('layout.master')
@section('body-class', 'page-categories')
@section('title', $category->description->meta_title ?: system_setting('base.meta_title', 'BeikeShop开源好用的跨境电商系统 - BeikeShop官网') .' - '. $category->description->name)
@section('keywords', $category->description->meta_keywords ?: system_setting('base.meta_keyword'))
@section('description', $category->description->meta_description ?: system_setting('base.meta_description'))

@push('header')
  <script src="{{ asset('vendor/jquery/jquery-ui/jquery-ui.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/jquery/jquery-ui/jquery-ui.min.css') }}">
@endpush

@section('content')
  <div class="container">

    <x-shop-breadcrumb type="category" :value="$category" />

    <div class="row">
      <div class="col-12 col-lg-3 pe-lg-4 left-column">
        <div class="mb-4 module-category-wrap">
          <h4 class="mb-3"><span>{{ __('product.category') }}</span></h4>
          <ul class="sidebar-widget mb-0" id="category-one">
            @foreach ($all_categories as $key_a => $category_all)
            <li class="{{ $category_all['id'] == $category->id ? 'active' : ''}}">
              <a href="{{ shop_route('categories.show', [$category_all['id']]) }}" title="{{ $category_all['name'] }}" class="category-href">{{ $category_all['name'] }}</a>
              @if ($category_all['children'])
                <button class="toggle-icon btn {{ $category_all['id'] == $category->id ? '' : 'collapsed'}}" data-bs-toggle="collapse" href="#category-{{ $key_a }}"><i class="bi bi-chevron-up"></i></button>
                <ul id="category-{{ $key_a }}" class="accordion-collapse collapse {{ $category_all['id'] == $category->id ? 'show' : ''}}" data-bs-parent="#category-one">
                  @foreach ($category_all['children'] as $key_b => $child)
                  <li class="{{ $child['id'] == $category->id ? 'active' : ''}} child-category">
                    <a href="{{ shop_route('categories.show', [$child['id']]) }}" title="{{ $child['name'] }}">{{ $child['name'] }}</a>
                  </li>
                  @endforeach
                </ul>
              @endif
            </li>
            @endforeach
          </ul>
        </div>

        <div class="filter-box">
          @if ($filter_data['price']['min'] != $filter_data['price']['max'])
            <div class="card">
              <div class="card-header p-0">
                <h4 class="mb-3">{{ __('product.price') }}</h4>
              </div>
              <div class="card-body p-0">
                <div class="text-secondary mb-3 price-range">
                  {{ currency_format($filter_data['price']['select_min'], current_currency_code()) }}
                  -
                  {{ currency_format($filter_data['price']['select_max'], current_currency_code()) }}
                </div>
                <input value="{{ $filter_data['price']['select_min'] }}" class="price-min d-none">
                <input value="{{ $filter_data['price']['select_max'] }}" class="price-max d-none">
                <div id="slider" class="mb-2"></div>
              </div>
            </div>
          @endif

          @foreach ($filter_data['attr'] as $index => $attr)
          <div class="card">
            <div class="card-header fw-bold p-0">
              <h4 class="mb-3">{{ $attr['name'] }}</h4>
            </div>
            <ul class="list-group list-group-flush attribute-item" data-attribute-id="{{ $attr['id'] }}">
              @foreach ($attr['values'] as $value_index => $value)
              <li class="list-group-item border-0 px-0">
                <label class="form-check-label d-block">
                  <input class="form-check-input attr-value-check me-2" data-attr="{{ $index }}" data-attrval="{{ $value_index }}" {{ $value['selected'] ? 'checked' : '' }} name="6" type="checkbox" value="{{ $value['id'] }}">{{ $value['name'] }}
                </label>
              </li>
              @endforeach
            </ul>
          </div>
          @endforeach
        </div>
      </div>

      <div class="col-12 col-lg-9 right-column">
        <div class="filter-value-wrap mb-2 d-none">
          <ul class="list-group list-group-horizontal">
            @foreach ($filter_data['attr'] as $index => $attr)
              @foreach ($attr['values'] as $value_index => $value)
                @if ($value['selected'])
                <li class="list-group-item me-1 mb-1" data-attr="{{ $index }}" data-attrval="{{ $value_index }}">
                  {{ $attr['name'] }}: {{ $value['name'] }} <i class="bi bi-x-lg ms-1"></i>
                </li>
                @endif
              @endforeach
            @endforeach
            <li class="list-group-item me-1 mb-1 delete-all">{{ __('common.delete_all') }}</li>
          </ul>
        </div>

        @include('shared.filter_bar_block')

        @if (count($products_format))
        <div class="row {{ request('style_list') == 'list' ? 'product-list-wrap' : ''}}">
          @foreach ($products_format as $product)
            <div class="{{ !request('style_list') || request('style_list') == 'grid' ? 'col-6 col-md-4' : 'col-12'}}">
              @include('shared.product')
            </div>
          @endforeach
        </div>
        @else
        <x-shop-no-data />
        @endif
      </div>
    </div>

    {{ $products->links('shared/pagination/bootstrap-4') }}

  </div>

@endsection

@push('add-scripts')
<script>
  let filterAttr = @json($filter_data['attr'] ?? []);

  $('.filter-value-wrap li').click(function(event) {
    let [attr, val] = [$(this).data('attr'),$(this).data('attrval')];
    if ($(this).hasClass('delete-all')) {
      deleteFilterAll();
      return;
    }

    filterAttr[attr].values[val].selected = false;
    filterProductData();
  });

  if ($('.filter-value-wrap li').length > 1) {
    $('.filter-value-wrap').removeClass('d-none')
  }

  $(document).ready(function () {
    $("#slider").slider({
      range: true,
      min: {{ $filter_data['price']['min'] ?? 0 }},
      max: {{ $filter_data['price']['max'] ?? 0 }},
      values: [{{ $filter_data['price']['select_min'] }}, {{ $filter_data['price']['select_max'] }}],
      change: function(event, ui) {
        $('input.price-min').val(ui.values[0])
        $('input.price-max').val(ui.values[1])
        filterProductData();
      },
      slide: function(event, ui) {
        $('.price-range').html(`${ui.values[0]} - ${ui.values[1]}`)
      }
    });
  });

  $('.child-category').each(function(index, el) {
    if ($(this).hasClass('active')) {
      $(this).parent('ul').addClass('show').siblings('button').removeClass('collapsed')
      $(this).parents('li').addClass('active')
    }
  });

  $('.attr-value-check').change(function(event) {
    let [attr, val] = [$(this).data('attr'),$(this).data('attrval')];
    filterAttr[attr].values[val].selected = $(this).is(":checked");
    filterProductData();
  });

  $('.form-select, input[name="style_list"]').change(function(event) {
    filterProductData();
  });

  function filterAttrChecked(data) {
    let filterAtKey = [];
    data.forEach((item) => {
      let checkedAtValues = [];

      item.values.forEach((val) => {
        if (val.selected) {
          checkedAtValues.push(val.id)
        }
      })

      if (checkedAtValues.length) {
        filterAtKey.push(`${item.id}:${checkedAtValues.join('/')}`)
      }
    })

    return filterAtKey.join('|')
  }

  function filterProductData() {
    let url = bk.removeURLParameters(window.location.href, 'attr', 'price', 'sort', 'order');
    let [priceMin, priceMax] = [$('.price-min').val(), $('.price-max').val()];
    let order = $('.order-select').val();
    let perpage = $('.perpage-select').val();
    let styleList = $('input[name="style_list"]:checked').val();

    layer.load(2, {shade: [0.3,'#fff'] })

    if (filterAttrChecked(filterAttr)) {
      url = bk.updateQueryStringParameter(url, 'attr', filterAttrChecked(filterAttr));
    }

    if (priceMin || priceMax) {
      url = bk.updateQueryStringParameter(url, 'price', `${priceMin}-${priceMax}`);
    }

    if (order) {
      let orderKeys = order.split('|');
      url = bk.updateQueryStringParameter(url, 'sort', orderKeys[0]);
      url = bk.updateQueryStringParameter(url, 'order', orderKeys[1]);
    }

    if (perpage) {
      url = bk.updateQueryStringParameter(url, 'per_page', perpage);
    }

    if (styleList) {
     url = bk.updateQueryStringParameter(url, 'style_list', styleList);
    }

    location = url;
  }

  function deleteFilterAll() {
    let url = bk.removeURLParameters(window.location.href, 'attr', 'price');
    location = url;
  }
</script>
@endpush