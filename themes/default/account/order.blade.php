@extends('layout.master')

@section('body-class', 'page-account-order-list')

@section('content')
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>
{{--     @foreach ($orders as $order)
        <div class="col-6 col-md-3">{{ $order->number }}</div>
    @endforeach --}}

    <div class="row">
      <div class="col-12 col-md-3">
        <div class="account-sides-info">
          <div class="head">
            <div class="portrait"><img src="" class="img-fluid"></div>
            <div class="account-name"></div>
            <div class="account-email"></div>
          </div>
          <nav class="list-group account-links">
            <a class="list-group-item d-flex justify-content-between align-items-center active" href="">
              <span>个人中心</span></a>
            <a class="list-group-item d-flex justify-content-between align-items-center" href="{{ shop_route('account.order.index') }}">
              <span>我的订单</span><span class="px-3 badge rounded-pill bg-dark">5</span></a>
            <a class="list-group-item d-flex justify-content-between align-items-center" href="">
              <span>我的收藏</span><span class="px-3 badge rounded-pill bg-dark">5</span></a>
            <a class="list-group-item d-flex justify-content-between align-items-center" href="">
              <span>我的收藏</span></a>
            <a class="list-group-item d-flex justify-content-between align-items-center" href="">
              <span>我的收藏</span></a>
            <a class="list-group-item d-flex justify-content-between align-items-center" href="">
              <span>我的收藏</span></a>
            <a class="list-group-item d-flex justify-content-between align-items-center" href="">
              <span>我的收藏</span></a>
            <a class="list-group-item d-flex justify-content-between align-items-center" href="{{ shop_route('logout') }}">
              <span>退出登录</span></a>
          </nav>
        </div>
      </div>
      <div class="col-12 col-md-9">
        <div class="card mb-4 account-card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">我的订单</h6>
          </div>
          <div class="card-body">

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
