@extends('layout.master')

@section('body-class', 'page-account-rmas')

@section('content')
  <x-shop-breadcrumb type="static" value="account.rma.index" />

  <div class="container">
    <div class="row">
      <x-shop-sidebar />
      <div class="col-12 col-md-9">
        <div class="card mb-4 account-card order-wrap h-min-600">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">{{ __('shop/account/rma.index') }}</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>{{ __('shop/account/rma.commodity') }}</th>
                    <th class="text-nowrap">{{ __('shop/account/rma.quantity') }}</th>
                    <th class="text-nowrap">{{ __('shop/account/rma.service_type') }}</th>
                    <th class="text-nowrap">{{ __('shop/account/rma.creation_time') }}</th>
                    <th class="text-end">{{ __('common.action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @if (count($rmas))
                    @foreach ($rmas as $rma)
                      <tr>
                        <td>
                          <div class="text-ellipsis line-2 w-min-100 w-max-300">{{ sub_string($rma['product_name'], 80) }}</div>
                        </td>
                        <td>{{ $rma['quantity'] }}</td>
                        <td>{{ $rma['type_format'] }}</td>
                        <td class="text-nowrap">{{ $rma['created_at'] }}</td>
                        <td class="text-end"><a href="{{ shop_route('account.rma.show', [$rma['id']]) }}"
                            class="btn text-nowrap btn-outline-secondary btn-sm">{{ __('shop/account/rma.check') }}</a> </td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="6" class="border-0">
                        <x-shop-no-data />
                      </td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>

            {{-- {{ $rmas->links('shared/pagination/bootstrap-4') }} --}}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
