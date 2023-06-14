<div class="product-wrap {{ request('style_list') ?? '' }}">
  <div class="image">
    <a href="{{ $product['url'] }}">
      <div class="image-old">
        <img
          data-sizes="auto"
          data-src="{{ $product['images'][0] ?? image_resize('', 400, 400) }}"
          src="{{ image_resize('', 400, 400) }}"
          class="img-fluid lazyload">
      </div>
    </a>
    @if (!request('style_list') || request('style_list') == 'grid')
      <div class="button-wrap">
        <button
          class="btn btn-dark text-light btn-quick-view mx-1 rounded-3"
          product-id="{{ $product['sku_id'] }}"
          product-price="{{ $product['price'] }}"
          data-bs-toggle="tooltip"
          data-bs-placement="top"
          title="{{ __('common.quick_view') }}"
          onclick="bk.productQuickView({{ $product['id'] }})">
          <i class="bi bi-eye"></i>
        </button>
        <button
          class="btn btn-dark text-light btn-wishlist mx-1 rounded-3"
          product-id="{{ $product['sku_id'] }}"
          product-price="{{ $product['price'] }}"
          data-bs-toggle="tooltip"
          data-bs-placement="top"
          title="{{ __('shop/products.add_to_favorites') }}"
          data-in-wishlist="{{ $product['in_wishlist'] }}"
          onclick="bk.addWishlist('{{ $product['id'] }}', this)">
          <i class="bi bi-heart{{ $product['in_wishlist'] ? '-fill' : '' }}"></i>
        </button>
        <button
          class="btn btn-dark text-light btn-add-cart mx-1 rounded-3"
          product-id="{{ $product['sku_id'] }}"
          product-price="{{ $product['price'] }}"
          data-bs-toggle="tooltip"
          data-bs-placement="top"
          title="{{ __('shop/products.add_to_cart') }}"
          onclick="bk.addCart({sku_id: '{{ $product['sku_id'] }}'}, this)">
          <i class="bi bi-cart"></i>
        </button>
      </div>
    @endif
  </div>
  <div class="product-bottom-info">
    @hook('product_list.item.name.before')
    <div class="product-name">{{ $product['name_format'] }}</div>
    <div class="product-price">
      <span class="price-new">{{ $product['price_format'] }}</span>
      @if ($product['price'] != $product['origin_price'] && $product['origin_price'] > 0)
        <span class="price-old">{{ $product['origin_price_format'] }}</span>
      @endif
    </div>

    @if (request('style_list') == 'list')
      <div class="button-wrap mt-3">
        <button
          class="btn btn-dark text-light"
          onclick="bk.addCart({sku_id: '{{ $product['sku_id'] }}'}, this)">
          <i class="bi bi-cart"></i>
          {{ __('shop/products.add_to_cart') }}
        </button>
      </div>

      <div>
        <button class="btn btn-link ps-0 text-secondary" data-in-wishlist="{{ $product['in_wishlist'] }}" onclick="bk.addWishlist('{{ $product['id'] }}', this)">
          <i class="bi bi-heart{{ $product['in_wishlist'] ? '-fill' : '' }} me-1"></i> {{ __('shop/products.add_to_favorites') }}
        </button>
      </div>
    @endif
  </div>
</div>
