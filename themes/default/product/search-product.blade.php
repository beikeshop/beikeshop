@if ($products)
  @foreach ($products as $product)
  <div class="{{ $class ?: 'col-lg-20' }}">
    @include('shared.product')
  </div>
  @endforeach

  @if (!isset($showAll) || $showAll)
  <div class="col-12 mt-3">
    <div class="search-pop-products-show-all d-flex justify-content-center">
      <button class="btn btn-lg">{{ __('common.show_all') }}</button>
    </div>
  </div>
  @endif
@else
<div style="margin-bottom: -28px"><x-shop-no-data /></div>
@endif