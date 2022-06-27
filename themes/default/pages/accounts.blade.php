@extends('layout.master')

@section('body-class', 'page-account')

@section('content')
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>

    <div class="row">
      <div class="col-3">
        <div class="account-info">
          <div class="head">
            <div class="portrait"><img src="http://fpoimg.com/120x120" class="img-fluid"></div>
            <div class="account-name">Pu shuo</div>
            <div class="account-email">229102104@qq.com</div>
          </div>
          <nav class="list-group account-links">
            <a class="list-group-item d-flex justify-content-between align-items-center active" href="">
              <span>个人中心</span></a>
            <a class="list-group-item d-flex justify-content-between align-items-center" href="">
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
          </nav>
        </div>
      </div>
      <div class="col-9">
        <div class="card mb-4 account-card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">个人中心</h6>
            <a href="http://" class="text-muted">修改休息</a>
          </div>
          <div class="card-body">
            <div class="d-flex flex-nowrap card-items">
              <a href="http://" class="d-flex flex-column align-items-center"><i class="iconfont">&#xe77f;</i><span
                  class="text-muted">收藏</span></a>
              <a href="http://" class="d-flex flex-column align-items-center"><i class="iconfont">&#xe6a3;</i><span
                  class="text-muted">优惠券</span></a>
              <a href="http://" class="d-flex flex-column align-items-center"><i class="iconfont">&#xe6a3;</i><span
                  class="text-muted">优惠券</span></a>
            </div>
          </div>
        </div>
        <div class="card account-card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">我的订单</h6>
            <a href="http://" class="text-muted">全部订单</a>
          </div>
          <div class="card-body">
            <div class="d-flex flex-nowrap card-items mb-4">
              <a href="http://" class="d-flex flex-column align-items-center"><i class="iconfont">&#xf12f;</i><span
                  class="text-muted">待付款</span></a>
              <a href="http://" class="d-flex flex-column align-items-center"><i class="iconfont">&#xf130;</i><span
                  class="text-muted">待发货</span></a>
              <a href="http://" class="d-flex flex-column align-items-center"><i class="iconfont">&#xf131;</i><span
                  class="text-muted">待收货</span></a>
              <a href="http://" class="d-flex flex-column align-items-center"><i class="iconfont">&#xf132;</i><span
                  class="text-muted">售后</span></a>
            </div>
            <div class="order-wrap">
              <div class="no-order d-flex flex-column align-items-center">
                <div class="icon mb-2"><i class="iconfont">&#xe60b;</i></div>
                <div class="text mb-3 text-muted">您还没有订单！<a href="">去下单</a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
