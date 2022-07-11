@extends('layout.master')

@section('body-class', 'page-product')

@section('content')

  <div class="container" id="checkout-app">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>

    <div class="row mb-5">
      <div class="col-12 col-md-6">
        <div class="product-image d-flex">
          <div class="left">
            @for ($i = 0; $i < 5; $i++)
              <div class=""><img src="http://fpoimg.com/100x100?bg_color=f3f3f3" class="img-fluid"></div>
            @endfor
          </div>
          <div class="right"><img src="{{ $product->image }}" class="img-fluid"></div>
        </div>
      </div>

      <div class="ps-lg-5 col-xl-5 col-lg-6 order-lg-2">
        <div class="peoduct-info">
          <h1>{{ $product->description->name }}</h1>
          <div class="rating-wrap d-flex">
            <div class="rating">
              @for ($i = 0; $i < 5; $i++)
              <i class="iconfont">&#xe628;</i>
              @endfor
            </div>
            <span class="text-muted">132 reviews</span>
          </div>
          <div class="price-wrap d-flex align-items-end">
            <div class="new-price">{{ $product->master_sku->price }}</div>
            <div class="old-price text-muted text-decoration-line-through">{{ $product->master_sku->origin_price }}</div>
          </div>
          <div class="attribute-wrap">
            <table class="table table-striped table-borderless">
              <tbody>
                <tr>
                  <td>型号</td>
                  <td>{{ $product->master_sku->model }}</td>
                </tr>
                <tr>
                  <td>Sku</td>
                  <td>{{ $product->master_sku->sku }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="quantity-btns d-flex">
            @include('shared.quantity', ['quantity' => '1'])
            <button class="btn btn-outline-secondary ms-3 add-cart"><i class="bi bi-cart-fill me-1"></i>加入购物车</button>
            <button class="btn btn-dark ms-3"><i class="bi bi-bag-fill me-1"></i>立即购买</button>
          </div>
          <div class="add-wishlist">
            <button class="btn btn-link ps-0"><i class="bi bi-suit-heart-fill me-1"></i>加入收藏夹</button>
          </div>
        </div>
      </div>
    </div>

    <div class="product-description">
      <div class="nav nav-tabs nav-overflow justify-content-start justify-content-md-center border-bottom">
        <a class="nav-link active" data-bs-toggle="tab" href="#product-description">
          Description
        </a>
        <a class="nav-link" data-bs-toggle="tab" href="#description-1">
          Size &amp; Fit
        </a>
        <a class="nav-link" data-bs-toggle="tab" href="#description-2">
          Shipping &amp; Return
        </a>
      </div>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="product-description" role="tabpanel" aria-labelledby="pills-home-tab">111</div>
        <div class="tab-pane fade" id="description-1" role="tabpanel" aria-labelledby="pills-profile-tab">222</div>
        <div class="tab-pane fade" id="description-2" role="tabpanel" aria-labelledby="pills-contact-tab">333</div>
      </div>
    </div>
  </div>

  <script>
    $('.add-cart').on('click', function(event) {
      const data = {
        sku_id: '{{ $product->master_sku->id }}',
        quantity: $('input[name="quantity"]').val()
      };

      $http.post('/carts', data).then((res) => {
        layer.msg(res.message)
      })
    });

  </script>
@endsection

