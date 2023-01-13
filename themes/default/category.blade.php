@extends('layout.master')
@section('body-class', 'page-categories')
@section('title', $category->description->meta_title ?: system_setting('base.meta_title', 'BeikeShop开源好用的跨境电商系统 - BeikeShop官网') .' - '. $category->description->name)
@section('keywords', $category->description->meta_keywords ?: system_setting('base.meta_keyword'))
@section('description', $category->description->meta_description ?: system_setting('base.meta_description'))

@section('content')
  <div class="container">

    <x-shop-breadcrumb type="category" :value="$category" />

    <div class="row">
      <div class="col-12 col-lg-3 pe-lg-4 left-column d-none d-lg-block">
        @include('shared.filter_sidebar_block')
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

        @if (count($products_format))
          @include('shared.filter_bar_block')
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

        {{ $products->links('shared/pagination/bootstrap-4') }}
      </div>
    </div>
  </div>

@endsection

@push('add-scripts')
<script>
  let filterAttr = @json($filter_data['attr'] ?? []);

  $('.filter-value-wrap li').click(function(event) {
    let [attr, val] = [$(this).data('attr'),$(this).data('attrval')];
    if ($(this).hasClass('delete-all')) {
      return deleteFilterAll();
    }

    filterAttr[attr].values[val].selected = false;
    filterProductData();
  });

  if ($('.filter-value-wrap li').length > 1) {
    $('.filter-value-wrap').removeClass('d-none')
  }

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

  function filterProductData() {
    let url = bk.removeURLParameters(window.location.href, 'attr', 'price', 'sort', 'order');
    let [psMin, psMax, pMin, pMax] = [$('.price-select-min').val(), $('.price-select-max').val(), $('.price-min').val(), $('.price-max').val()];
    let order = $('.order-select').val();
    let perpage = $('.perpage-select').val();
    let styleList = $('input[name="style_list"]:checked').val();

    layer.load(2, {shade: [0.3,'#fff'] })

    if (filterAttrChecked(filterAttr)) {
      url = bk.updateQueryStringParameter(url, 'attr', filterAttrChecked(filterAttr));
    }

    if ((psMin != pMin) || (psMax != pMax)) {
      url = bk.updateQueryStringParameter(url, 'price', `${psMin}-${psMax}`);
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

  function filterAttrChecked(data) {
    let filterAtKey = [];
    data.forEach((item) => {
      let checkedAtValues = [];
      item.values.forEach((val) => val.selected ? checkedAtValues.push(val.id) : '')
      if (checkedAtValues.length) {
        filterAtKey.push(`${item.id}:${checkedAtValues.join('/')}`)
      }
    })

    return filterAtKey.join('|')
  }

  function deleteFilterAll() {
    let url = bk.removeURLParameters(window.location.href, 'attr', 'price');
    location = url;
  }
</script>
@endpush