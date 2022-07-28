<div class="product-wrap">
  <div class="image"><a href="{{ $product['url'] }}"><img src="{{ $product['images'][0] ?? image_resize('', 400, 400) }}" class="img-fluid"></a></div>
  <div class="product-name">{{ $product['name'] }}</div>
  <div class="product-price">
    <span class="price-new">{{ $product['price_format'] }}</span>
    <span class="price-lod">{{ $product['origin_price_format'] }}</span>
  </div>
</div>
