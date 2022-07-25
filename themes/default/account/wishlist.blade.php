@extends('layout.master')

@section('body-class', 'page-account-wishlist')

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
        <div class="card mb-4 account-card order-wrap">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">我的订单</h5>
          </div>
          <div class="card-body">
            <table class="table ">
              <thead>
                <tr>
                  <th>订单详情</th>
                  <th>金额</th>
                  <th>状态</th>
                  <th class="text-end">操作</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
