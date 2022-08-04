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
      <a href="" class="btn-left">
        <i class="iconfont">&#xe77f;</i>
        <span>加入收藏</span>
      </a>
      <a href="" class="btn-right">
        <i class="iconfont">&#xf13a;</i>
        <span>加入购物车</span>
      </a>
    </div>
  </div>
  <div class="product-name">{{ $product['name'] }}</div>
  <div class="product-price">
    <span class="price-new">{{ $product['price_format'] }}</span>
    <span class="price-lod">{{ $product['origin_price_format'] }}</span>
  </div>
</div>