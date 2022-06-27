@extends('layout.master')

@section('body-class', 'page-product')

@section('content')
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>

    <div class="row">
      <div class="col-12 col-md-6">
        <div class="product-image d-flex">
          <div class="left">
            @for ($i = 0; $i < 5; $i++)
              <div class=""><img src="http://fpoimg.com/100x100?bg_color=f3f3f3" class="img-fluid"></div>
            @endfor
          </div>
          <div class="right"><img src="http://fpoimg.com/560x560?bg_color=f3f3f3" class="img-fluid"></div>
        </div>
      </div>
      <div class="col-12 col-md-6 ps-lg-10">
        <div class="peoduct-info">
          <h1>Super Oversized T-Shirt With Raw Sleeves In Brown</h1>
          <div class="rating-wrap d-flex">
            <div class="rating">
              @for ($i = 0; $i < 5; $i++)
              <i class="iconfont">&#xe628;</i>
              @endfor
            </div>
            <span class="text-muted">132 reviews</span>
          </div>
          <div class="price-wrap d-flex align-items-end">
            <div class="new-price">$815.00</div>
            <div class="old-price text-muted text-decoration-line-through">$1015.00</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
