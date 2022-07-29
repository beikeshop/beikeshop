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

      <x-shop-sidebar/>

      <div class="col-12 col-md-9">
        @if (\Session::has('success'))
          <div class="alert alert-success">
            <ul><li>{!! \Session::get('success') !!}</li></ul>
          </div>
        @endif
        <div class="card mb-4 account-card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">个人中心</h5>
            <a href="http://" class="text-muted">修改信息</a>
          </div>
          <div class="card-body">
            <div class="d-flex flex-nowrap card-items py-2">
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
            <h5 class="card-title">我的订单</h5>
            <a href="http://" class="text-muted">全部订单</a>
          </div>
          <div class="card-body">
            <div class="d-flex flex-nowrap card-items mb-4 py-2">
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
