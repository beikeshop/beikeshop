@extends('layout.master')

@section('body-class', 'page-checkout')

@section('content')
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>

    <div class="row justify-content-center">
      <div class="col-12 col-md-9">@include('shared.steps', ['steps' => 2])</div>
    </div>

    <div class="row mt-5">
      <div class="col-12 col-md-8">
        <form action="">
          <div class="checkout-address">
            <h4 class="title">地址</h4>
            <div class="row mb-3">
              <div class="col-12 col-md-6 mb-3">
                <div class="form-floating">
                  <input type="text" name="email" class="form-control" value="" placeholder="姓名">
                  <label class="form-label" for="email_1">姓名</label>
                </div>
              </div>
              <div class="col-12 col-md-6 mb-3">
                <div class="form-floating">
                  <input type="text" name="email" class="form-control" value="" placeholder="电话">
                  <label class="form-label" for="email_1">电话</label>
                </div>
              </div>
              <div class="col-12 col-md-4 mb-3">
                <div class="form-floating">
                  <select class="form-select" aria-label="Default select example" name="county">
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                  <label class="form-label" for="email_1">county</label>
                </div>
              </div>
              <div class="col-12 col-md-4 mb-3">
                <div class="form-floating">
                  <select class="form-select" aria-label="Default select example" name="zone">
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                  <label class="form-label" for="email_1">zone</label>
                </div>
              </div>
              <div class="col-12 col-md-4 mb-3">
                <div class="form-floating">
                  <input type="text" name="email" class="form-control" value="" placeholder="city">
                  <label class="form-label" for="email_1">city</label>
                </div>
              </div>
              <div class="col-12 mb-3">
                <div class="form-floating">
                  <input type="text" name="email" class="form-control" value="" placeholder="city">
                  <label class="form-label" for="email_1">邮编</label>
                </div>
              </div>
              <div class="col-12 mb-3">
                <div class="form-floating">
                  <input type="text" name="email" class="form-control" value="" placeholder="city">
                  <label class="form-label" for="email_1">address 1</label>
                </div>
              </div>
              <div class="col-12 mb-3">
                <div class="form-floating">
                  <input type="text" name="email" class="form-control" value="" placeholder="city">
                  <label class="form-label" for="email_1">address 2</label>
                </div>
              </div>
            </div>

            <h4 class="title">支付方式</h4>
            <div class="row mb-3">
              <div class="mb-4">
                <input type="radio" class="btn-check" name="options-outlined" id="success-outlined" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="success-outlined">支付方式 - 1</label>

                <input type="radio" class="btn-check" name="options-outlined" id="danger-outlined" autocomplete="off">
                <label class="btn btn-outline-primary" for="danger-outlined">支付方式 - 2</label>
              </div>
            </div>

            <h4 class="title">配送方式</h4>
            <div class="row mb-3">
              <div class="mb-4">
                <input type="radio" class="btn-check" name="peisong_name" id="peisong-1" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="peisong-1">配送方式 - 1</label>

                <input type="radio" class="btn-check" name="peisong_name" id="peisong-2" autocomplete="off">
                <label class="btn btn-outline-primary" for="peisong-2">配送方式 - 2</label>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="col-12 col-md-4">
        <div class="card total-wrap">
          <div class="card-header"><h5 class="mb-0">CART TOTALS</h5></div>
          <div class="card-body">
            <div class="products-wrap">
              @for ($i = 0; $i < 4; $i++)
              <div class="item">
                <div class="image">
                  <img src="http://fpoimg.com/100x100?bg_color=f3f3f3" class="img-fluid">
                  <div class="name">
                    <span>Camera Canon EOS M50 Kit</span>
                    <span class="quantity">x2</span>
                  </div>
                </div>
                <div class="price">$1156.00</div>
              </div>
              @endfor
            </div>
            <ul class="totals">
              <li><span>总数</span><span>1120</span></li>
              <li><span>运费</span><span>20</span></li>
              <li><span>总价</span><span>2220</span></li>
            </ul>
            <div class="d-grid gap-2 mt-3">
              <button class="btn btn-primary">提交订单</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
