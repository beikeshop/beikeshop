<div class="product-wrap">
  <div class="image">
    <a href="{{ $product['url'] }}">
      <div class="image-old">
        <img src="{{ $product['images'][0] ?? image_resize('', 400, 400) }}" class="img-fluid">
      </div>
      <div class="image-hover">
        <img src="{{ $product['images'][1] ?? image_resize('', 400, 400) }}" class="img-fluid">
      </div>
    </a>
    <div class="button-wrap">
      <button
        class="btn btn-dark text-light mx-1 rounded-3"
        data-bs-toggle="tooltip"
        data-bs-placement="top"
        title="{{ __('shop/products.add_to_favorites') }}"
        data-in-wishlist="{{ $product['in_wishlist'] }}"
        onclick="bk.addWishlist('{{ $product['id'] }}', this)">
        <i class="bi bi-heart{{ $product['in_wishlist'] ? '-fill' : '' }}"></i>
      </button>
      <button
        class="btn btn-dark text-light mx-1 rounded-3"
        data-bs-toggle="tooltip"
        data-bs-placement="top"
        title="{{ __('shop/products.add_to_cart') }}"
        onclick="bk.addCart({sku_id: '{{ $product['sku_id'] }}'}, this)">
        <i class="bi bi-cart"></i>
      </button>
    </div>
  </div>
  <div class="product-name">{{ $product['name_format'] }}</div>
  <div class="product-price">
    <span class="price-new">{{ $product['price_format'] }}</span>
    <span class="price-lod">{{ $product['origin_price_format'] }}</span>
  </div>
</div>