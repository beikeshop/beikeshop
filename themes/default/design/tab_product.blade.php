<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @if ($design)
  <div class="module-edit">
    <div class="edit-wrap">
      <div class="down"><i class="bi bi-chevron-down"></i></div>
      <div class="up"><i class="bi bi-chevron-up"></i></div>
      <div class="delete"><i class="bi bi-x-lg"></i></div>
      <div class="edit"><i class="bi bi-pencil-square"></i></div>
    </div>
  </div>
  @endif
  <div class="module-info module-tab-product">
    <div class="module-title">{{ $content['title'] }}</div>
    <div class="container">
      @if ($content['tabs'])
        <div class="nav justify-content-center mb-3">
          @foreach ($content['tabs'] as $key => $tabs)
          <a class="nav-link {{ $loop->first ? 'active' : '' }}" href="#tab-product-{{ $loop->index }}" data-bs-toggle="tab">{{ $tabs['title'] }}</a>
          @endforeach
        </div>
        <div class="tab-content">
          @foreach ($content['tabs'] as $products)
          <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}" id="tab-product-{{ $loop->index }}">
            <div class="row">
              @if ($products['products'])
                @foreach ($products['products'] as $product)
                <div class="col-6 col-md-4 col-lg-3">
                  @include('shared.product')
                </div>
                @endforeach
              @elseif (!$products['products'] and $design)
                @for ($s = 0; $s < 8; $s++)
                <div class="col-6 col-md-4 col-lg-3">
                  <div class="product-wrap">
                    <div class="image"><a href="javascript:void(0)"><img src="{{ asset('catalog/placeholder.png') }}" class="img-fluid"></a></div>
                    <div class="product-name">请配置商品</div>
                    <div class="product-price">
                      <span class="price-new">66.66</span>
                      <span class="price-lod">99.99</span>
                    </div>
                  </div>
                </div>
                @endfor
              @endif
            </div>
          </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</section>



