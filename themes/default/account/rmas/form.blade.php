@extends('layout.master')

@section('body-class', 'page-account-rmas')

@push('header')
  {{-- <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script> --}}
@endpush

@section('content')
  <div class="container" id="address-app">

    <x-shop-breadcrumb type="static" value="account.rma.index" /> 

    {{-- <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav> --}}


    <div class="row">
      <x-shop-sidebar/>

      <div class="col-12 col-md-9">
        <div class="card h-min-600">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">{{ __('shop/account.rma.rma_form.index') }}</h5>
          </div>
          <div class="card-body h-min-600">
            <div class="bg-light rounded-3 p-3 mb-4" style="background: #f6f9fc;">
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

            <form action="{{ shop_route('account.rma.store') }}" method="POST">
              @csrf
              {{-- {{ method_field('put') }} --}}

              @if (session('success'))
                <x-shop-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
              @endif

              <input type="hidden" name="order_product_id" value="{{ $orderProduct->id }}">

              <div class="row">
                <div class="col-sm-6 mb-4">
                  <label class="form-label">{{ __('shop/account.rma.rma_form.service_type') }}</label>
                  <select class="form-select" name="type">
                    @foreach ($types as $key => $item)
                      <option value="{{ $key }}" {{ $key == old('type', '') ? 'selected': '' }}>{{ $item }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-sm-6 mb-4">
                  <label class="form-label">{{ __('shop/account.rma.rma_form.return_quantity') }}</label>
                  <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="text" name="quantity" value="{{ old('quantity', $orderProduct->quantity ?? '1') }}">
                  @if ($errors->has('quantity'))
                    <span class="invalid-feedback" role="alert">{{ $errors->first('quantity') }}</span>
                  @endif
                </div>

                <div class="col-sm-6 mb-4">
                  <label class="form-label">{{ __('shop/account.rma.rma_form.unpacked') }}</label>
                  <select class="form-select" name="opened">
                    <option selected value="0">{{ __('common.no') }}</option>
                    <option value="1">{{ __('common.yes') }}</option>
                  </select>
                </div>

                <div class="col-sm-6 mb-4">
                  <label class="form-label">{{ __('shop/account.rma.rma_form.return_reason') }}</label>
                  <select class="form-select {{ $errors->has('rma_reason_id') ? 'is-invalid' : '' }}" name="rma_reason_id">
                    @foreach ($reasons as $item)
                      <option value="{{ $item['id'] }}" {{ $item['id'] == old('opened', '') ? 'selected': '' }}>{{ $item['name'] }}</option>
                    @endforeach
                  </select>

                  @if ($errors->has('rma_reason_id'))
                    <span class="invalid-feedback" role="alert">{{ $errors->first('rma_reason_id') }}</span>
                  @endif
                </div>

                <div class="col-12 "></div>

                <div class="col-sm-6 mb-4">
                  <label class="form-label">{{ __('shop/account.rma.rma_form.remark') }}</label>
                  <textarea rows="4" type="text" name="comment" class="form-control">{{ old('comment', '') }}</textarea>
                </div>

                <div class="col-12 mt-4">
                  <button class="btn btn-primary mt-sm-0" type="submit">{{ __('shop/common.submit') }}</button>
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
