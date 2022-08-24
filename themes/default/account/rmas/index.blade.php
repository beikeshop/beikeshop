@extends('layout.master')

@section('body-class', 'page-account-rmas')

@section('content')
  <div class="container">

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
        <div class="card mb-4 account-card order-wrap h-min-600">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">{{ __('shop/account.rma.index') }}</h5>
          </div>
          <div class="card-body">
            <table class="table ">
              <thead>
                <tr>
                  <th>{{ __('shop/account.rma.commodity') }}</th>
                  <th>{{ __('shop/account.rma.quantity') }}</th>
                  <th>{{ __('shop/account.rma.service_type') }}</th>
                  <th>{{ __('shop/account.rma.return_reason') }}</th>
                  <th>{{ __('shop/account.rma.creation_time') }}</th>
                  {{-- <th>状态</th> --}}
                  <th class="text-end">{{ __('common.action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($rmas as $rma)
                  <tr>
                    <td>{{ sub_string($rma['product_name'], 80) }}</td>
                    <td>{{ $rma['quantity'] }}</td>
                    <td>{{ $rma['type'] }}</td>
                    <td>{{ $rma['reason'] }}</td>
                    <td>{{ $rma['created_at'] }}</td>
                    {{-- <td>{{ $rma['status'] }}</td> --}}
                    <td class="text-end"><a href="{{ shop_route('account.rma.show', [$rma['id']]) }}" class="btn btn-outline-secondary btn-sm">{{ __('shop/account.rma.check') }}</a> </td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            {{-- {{ $rmas->links('shared/pagination/bootstrap-4') }} --}}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
