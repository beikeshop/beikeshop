<div class="mb-4 module-category-wrap">
  <h4 class="mb-3"><span>{{ __('product.category') }}</span></h4>
  <ul class="sidebar-widget mb-0" id="category-one">
    @foreach ($all_categories as $key_a => $category_all)
    <li class="{{ $category_all['id'] == $category->id ? 'active' : ''}}">
      <a href="{{ $category_all['url'] }}" title="{{ $category_all['name'] }}" class="category-href">{{ $category_all['name'] }}</a>
      @if ($category_all['children'] ?? false)
        <button class="toggle-icon btn {{ $category_all['id'] == $category->id ? '' : 'collapsed'}}" data-bs-toggle="collapse" href="#category-{{ $key_a }}"><i class="bi bi-chevron-up"></i></button>
        <ul id="category-{{ $key_a }}" class="accordion-collapse collapse {{ $category_all['id'] == $category->id ? 'show' : ''}}" data-bs-parent="#category-one">
          @foreach ($category_all['children'] as $key_b => $child)
          <li class="{{ $child['id'] == $category->id ? 'active' : ''}} child-category">
            <a href="{{ $child['url'] }}" title="{{ $child['name'] }}" class="category-href">{{ $child['name'] }}</a>
            @if ($child['children'] ?? false)
              <button class="toggle-icon btn {{ $child['id'] == $category->id ? '' : 'collapsed'}}" data-bs-toggle="collapse" href="#category-{{ $key_a }}-{{ $key_b }}"><i class="bi bi-chevron-up"></i></button>
              <ul id="category-{{ $key_a }}-{{ $key_b }}" class="accordion-collapse collapse {{ $child['id'] == $category->id ? 'show' : ''}}" data-bs-parent="#category-{{ $key_a }}">
                @foreach ($child['children'] as $key_c => $sub_child)
                <li class="{{ $sub_child['id'] == $category->id ? 'active' : ''}} child-category">
                  <a href="{{ $sub_child['url'] }}" title="{{ $sub_child['name'] }}" class="category-href">{{ $sub_child['name'] }}</a>
                </li>
                @endforeach
              </ul>
            @endif
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
    @hookwrapper('category.filter.sidebar.price')
    @push('header')
      <script src="{{ asset('vendor/jquery/jquery-ui/jquery-ui.min.js') }}"></script>
      <link rel="stylesheet" href="{{ asset('vendor/jquery/jquery-ui/jquery-ui.min.css') }}">
    @endpush

    @if (system_setting('base.multi_filter.price_filter', 1))
      <div class="card">
        <div class="card-header p-0">
          <h4 class="mb-3">{{ __('product.price') }}</h4>
        </div>
        <div class="card-body p-0">
          <div id="price-slider" class="mb-2"><div class="slider-bg"></div></div>
          <div class="text-secondary price-range d-flex justify-content-between">
            <div>
              {{ __('common.text_form') }}
              <span class="min">{{ currency_format($filter_data['price']['select_min'], current_currency_code()) }}</span>
            </div>
            <div>
              {{ __('common.text_to') }}
              <span class="max">{{ currency_format($filter_data['price']['select_max'], current_currency_code()) }}</span>
            </div>
          </div>
          <input value="{{ $filter_data['price']['select_min'] }}" class="price-select-min d-none">
          <input value="{{ $filter_data['price']['select_max'] }}" class="price-select-max d-none">
          <input value="{{ $filter_data['price']['min'] }}" class="price-min d-none">
          <input value="{{ $filter_data['price']['max'] }}" class="price-max d-none">
        </div>
      </div>
    @endif
    @endhookwrapper
  @endif

  @hookwrapper('category.filter.sidebar.attr')
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
  @endhookwrapper
</div>

@push('add-scripts')
<script>
  const currencyRate = {{ current_currency_rate() }};
  $(document).ready(function() {
    if (!$('#price-slider').length) {
      return;
    }

    $("#price-slider").slider({
      range: true,
      step: 0.01,
      min: {{ $filter_data['price']['min'] ?? 0 }},
      max: {{ $filter_data['price']['max'] ?? 0 }},
      values: [{{ $filter_data['price']['select_min'] }}, {{ $filter_data['price']['select_max'] }}],
      change: function(event, ui) {
        $('input.price-select-min').val(ui.values[0])
        $('input.price-select-max').val(ui.values[1])
        filterProductData();
      },
      slide: function(event, ui) {
        let min = $('.price-range .min').html();
        let max = $('.price-range .max').html();
        $('.price-range .min').html(min.replace(min.replace(/[^0-9.,]/g, ''), (ui.values[0] * currencyRate).toFixed(2)));
        $('.price-range .max').html(max.replace(max.replace(/[^0-9.,]/g, ''), (ui.values[1] * currencyRate).toFixed(2)));
      }
    });
  })

  $('.child-category').each(function(index, el) {
    if ($(this).hasClass('active')) {
      $(this).parents('ul').addClass('show').siblings('button').removeClass('collapsed')
      $(this).parents('li').addClass('active')
    }
  });
</script>
@endpush
