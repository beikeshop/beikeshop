@extends('layout.master')

@section('body-class', 'page-account-rmas')

@push('header')
  {{-- <script src="{{ asset('vendor/vue/2.7/vue' . (!config('app.debug') ? '.min' : '') . '.js') }}"></script> --}}
@endpush

@section('content')
  <x-shop-breadcrumb type="rma" value="{{ $rma['id'] }}" />

  <div class="container" id="address-app">
    <div class="row">
      <x-shop-sidebar />

      <div class="col-12 col-md-9">
        <div class="card h-min-600">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">{{ __('shop/account/rma_info.index') }}</h5>
          </div>
          <div class="card-body h-600">
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
            <div class="row">
              <div class="col-6 mb-4">
                <label class="form-label">{{ __('shop/account/rma_form.service_type') }}</label>
                <div>{{ $rma['type_format']}}</div>
                </div>

                <div class="col-6 mb-4">
                  <label class="form-label">{{ __('shop/account/rma_form.return_quantity') }}</label>
                  <div>{{ $rma['quantity'] }}</div>
              </div>

              <div class="col-6 mb-4">
                <label class="form-label">{{ __('common.status') }}</label>
                <div>{{ $rma['status_format'] }}</div>
              </div>

              <div class="col-6 mb-4">
                <label class="form-label">{{ __('shop/account/rma_form.unpacked') }}</label>
                <div>
                  @if ($rma['opened'])
                  {{ __('common.yes') }}
                  @else
                  {{ __('common.no') }}
                  @endif
                </div>
              </div>

              <div class="col-6 mb-4">
                <label class="form-label">{{ __('shop/account/rma.creation_time') }}</label>
                <div>
                  {{ $rma['created_at'] }}
                </div>
              </div>

              <div class="col-6 mb-4">
                <label class="form-label">{{ __('shop/account/rma_form.return_reason') }}</label>
                <div>
                  {{$rma['reason']}}
                </div>
              </div>

              <div class="col-6 mb-4">
                <label class="form-label">{{ __('shop/account/rma_form.remark') }}</label>
                <div>{{$rma['comment']}}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('add-scripts')
@endpush
