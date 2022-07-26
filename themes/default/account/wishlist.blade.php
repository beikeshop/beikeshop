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
        <div class="card mb-4 account-card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">我的收藏</h5>
          </div>
          <div class="card-body">
            <table class="table ">
              <thead>
                <tr>
                  <th width="120px"></th>
                  <th>产品</th>
                  <th>价格</th>
                  <th class="text-end"></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($wishlist as $item)
                <tr>
                  <td><div class="wp-100 hp-100"><img src="{{ $item['image'] }}" class="img-fluid"></div></td>
                  <td>{{ $item['product_name'] }}</td>
                  <td>{{ $item['price'] }}</td>
                  <td class="text-end">
                    <div class="">
                      <button class="btn btn-dark btn-sm">加购物车</button>
                      <button class="btn btn-danger btn-sm"><i class="bi bi-x-lg"></i></button>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>
@endsection
