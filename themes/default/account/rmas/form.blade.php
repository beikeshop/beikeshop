@extends('layout.master')

@section('body-class', 'page-account-rmas')

@push('header')
  {{-- <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script> --}}
@endpush

@section('content')
  <div class="container" id="address-app">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>

    <div class="row">
      <x-shop-sidebar/>

      <div class="col-12 col-md-9">
        <div class="card h-min-600">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">提交售后信息</h5>
          </div>
          <div class="card-body h-600">
            <div class="bg-light rounded-3 p-3 mb-4" style="background: #f6f9fc;">
              {{-- <h6 class="mb-2">商品信息</h6> --}}
              <div class="d-flex align-items-center">
                <div class="left wh-70">
                  <img src="{{ $orderProduct->image }}" class="img-fluid">
                </div>
                <div class="right ms-3">
                  <div class="name mb-2 fw-bold fs-5">{{ $orderProduct->name }}</div>
                  <div class="price">{{ $orderProduct->price }} x {{ $orderProduct->quantity }}</div>
                </div>
              </div>
            </div>
            {{-- <h6 class="border-bottom pb-3 mb-4">商品信息 & 退换原因</h6> --}}
            <form action="{{ shop_route('account.rma.store') }}" method="POST">
              @csrf
              {{-- {{ method_field('put') }} --}}

              @if (session('success'))
                <x-shop-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
              @endif

              <input type="hidden" name="order_product_id" value="{{ $orderProduct->id }}">

              <div class="row">
                <div class="col-sm-6 mb-4">
                  <label class="form-label">服务类型</label>
                  <select class="form-select" name="type">
                    @foreach ($types as $key => $item)
                      <option value="{{ $key }}" {{ $key == old('type', '') ? 'selected': '' }}>{{ $item }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-sm-6 mb-4">
                  <label class="form-label">退换数量</label>
                  <input class="form-control" type="text" value="{{ old('quantity', $orderProduct->quantity ?? '1') }}">
                  @if ($errors->has('quantity'))
                    <span class="invalid-feedback" role="alert">{{ $errors->first('quantity') }}</span>
                  @endif
                </div>
                <div class="col-sm-6 mb-4">
                  <label class="form-label">已打开包装</label>
                  <select class="form-select" name="opened">
                    <option selected value="0">否</option>
                    <option value="1">是</option>
                  </select>
                </div>
                <div class="col-sm-6 mb-4">
                  <label class="form-label">退换原因</label>
                  <select class="form-select" name="opened">
                    <option selected value="0">否</option>
                    <option value="1">是</option>
                  </select>
                </div>

                <div class="col-12 "></div>

                <div class="col-sm-6 mb-4">
                  <label class="form-label">备注</label>
                  <textarea rows="4" type="text" name="comment" class="form-control">{{ old('comment', '') }}</textarea>
                </div>

                <div class="col-12 mt-4">
                  <button class="btn btn-primary mt-sm-0" type="submit">提交</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('add-scripts')

@endpush
