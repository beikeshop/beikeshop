@extends('layout.master')

@section('body-class', 'page-cart')

@section('content')
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>

    <div class="row justify-content-center">
      <div class="col-12 col-md-9">@include('shared.steps', ['steps' => 1])</div>
    </div>

    <div class="row mt-5">
      <div class="col-12 col-md-9">
        <div class="cart-products-wrap">
          <table class="table">
            <thead>
              <tr>
                <th width="130">
                  <input class="form-check-input" type="checkbox" value="" id="check-all">
                  <label class="form-check-label ms-1" for="check-all">
                    全选
                  </label>
                </th>
                <th>商品</th>
                <th>数量</th>
                <th>小计</th>
                <th class="align-right">操作</th>
              </tr>
            </thead>
            <tbody>
              @for ($i = 0; $i < 5; $i++)
              <tr>
                <td>
                  <div class="d-flex align-items-center p-image">
                    <input class="form-check-input" type="checkbox" value="">
                    <img src="http://fpoimg.com/100x100?bg_color=f3f3f3" class="img-fluid">
                  </div>
                </td>
                <td>
                  <div class="name mb-1 fw-bold">Camera Canon EOS M50 Kit</div>
                  <div class="price">$1156.00</div>
                </td>
                <td>
                  @include('shared.quantity', ['quantity'=>'2'])
                </td>
                <td>$1156.00</td>
                <td>
                  <button type="button" class="btn btn-link btn-sm">删除</button><br>
                  <button type="button" class="btn btn-link btn-sm">加入收藏</button>
                </td>
              </tr>
              @endfor
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-12 col-md-3">
        <div class="card total-wrap">
          <div class="card-header"><h5 class="mb-0">商品总计</h5></div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><span>总数</span><span>20</span></li>
              <li class="list-group-item border-bottom-0"><span>总价</span><span class="total-price">¥223.33</span></li>
              <li class="list-group-item d-grid gap-2 mt-3 border-bottom-0">
                <a href="{{ shop_route('pages.show', 'checkout') }}" class="btn btn-primary">去结账</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
