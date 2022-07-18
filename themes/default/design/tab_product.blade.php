<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @if ($design)
  <div class="module-edit">
    <div class="edit-wrap">
      <div class=""><i class="bi bi-chevron-down"></i></div>
      <div class=""><i class="bi bi-chevron-up"></i></div>
      <div class="delete"><i class="bi bi-x-lg"></i></div>
      <div class="edit"><i class="bi bi-pencil-square"></i></div>
    </div>
  </div>
  @endif

  <div class="module-info">
    <div class="module-title">推荐商品模块</div>
    <div class="container">
      @if (isset($content['tabs']))
        <div class="nav justify-content-center mb-3">
          @foreach ($content['tabs'] as $key => $category)
          <a class="nav-link {{ $loop->first ? 'active' : '' }}" href="#tab-product-{{ $loop->index }}" data-bs-toggle="tab">{{ $key }}</a>
          @endforeach
        </div>
        <div class="tab-content">
          @foreach ($content['tabs'] as $products)
          <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}" id="tab-product-{{ $loop->index }}">
            <div class="row">
              @foreach ($products as $product)
              <div class="col-6 col-md-4 col-lg-3">
                @include('shared.product')
              </div>
              @endforeach
            </div>
          </div>
          @endforeach
        </div>
      @else
      <div class="nav justify-content-center mb-3">
        @for ($i = 0; $i < 2; $i++)
        <a class="nav-link {{ $loop->first ? 'active' : '' }}" href="#tab-product-{{ $loop->index }}" data-bs-toggle="tab">标题</a>
        @endfor
      </div>
      <div class="tab-content">
        @for ($i = 0; $i < 2; $i++)
        <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}" id="tab-product-{{ $loop->index }}">
          <div class="row">
            @for ($s = 0; $s < 8; $s++)
            <div class="col-6 col-md-4 col-lg-3">
              @include('shared.product')
              商品
            </div>
            @endfor
          </div>
        </div>
        @endfor
      </div>
      @endif
    </div>
  </div>
</section>



