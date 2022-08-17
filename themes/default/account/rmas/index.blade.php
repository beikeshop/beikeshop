@extends('layout.master')

@section('body-class', 'page-account-rmas')

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
        <div class="card mb-4 account-card order-wrap h-min-600">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">我的售后</h5>
          </div>
          <div class="card-body">
            <table class="table ">
              <thead>
                <tr>
                  <th>商品</th>
                  <th>数量</th>
                  <th>服务类型</th>
                  <th>创建时间</th>
                  <th>状态</th>
                  <th class="text-end">操作</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($rmas as $rma)
                  <tr>
                    <td>{{ sub_string($rma['product_name'], 80) }}</td>
                    <td>{{ $rma['quantity'] }}</td>
                    <td>{{ $rma['type'] }}</td>
                    <td>{{ $rma['created_at'] }}</td>
                    <td>{{ $rma['status'] }}</td>
                    <td class="text-end"><a href="{{ shop_route('account.rma.show', [$rma['id']]) }}" class="btn btn-outline-secondary btn-sm">查看</a> </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
