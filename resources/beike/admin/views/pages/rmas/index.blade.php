@extends('admin::layouts.master')

@section('title', __('admin/rma.index'))

@section('content')
  <div id="customer-app" class="card h-min-600">
    <div class="card-body">
      @if (count($rmas))
        <table class="table">
          <thead>
            <tr>
              <th>{{ __('admin/rma.customers_name') }}</th>
              <th>{{ __('common.email') }}</th>
              <th>{{ __('common.phone') }}</th>
              <th>{{ __('admin/builder.modules_product') }}</th>
              <th>{{ __('product.sku') }}</th>
              <th>{{ __('admin/rma.quantity') }}</th>
              <th>{{ __('admin/rma.service_type') }}</th>
              <th>{{ __('common.status') }}</th>
              <th>{{ __('common.action') }}</th>
            </tr>
          </thead>
          <tbody>
            @if (count($rmas_format))
              @foreach ($rmas_format as $rma)
                <tr>
                  <td>{{ $rma['name'] }}</td>
                  <td>{{ $rma['email'] }}</td>
                  <td>{{ $rma['telephone'] }}</td>
                  <td>{{ $rma['product_name'] }}</td>
                  <td>{{ $rma['sku'] }}</td>
                  <td>{{ $rma['quantity'] }}</td>
                  <td>{{ $rma['type'] }}</td>
                  <td>{{ $rma['status'] }}</td>
                  <td><a href="{{ admin_route('rmas.show', [$rma['id']]) }}"
                      class="btn btn-outline-secondary btn-sm">{{ __('common.view') }}</a>
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="9" class="border-0">
                  <x-admin-no-data />
                </td>
              </tr>
            @endif
          </tbody>
        </table>
        {{ $rmas->links('admin::vendor/pagination/bootstrap-4') }}
      @else
        <x-admin-no-data />
      @endif
    </div>
  </div>
@endsection
