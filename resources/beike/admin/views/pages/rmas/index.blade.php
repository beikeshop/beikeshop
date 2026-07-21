@extends('admin::layouts.master')

@section('title', __('admin/rma.index'))

@section('content')
  <div id="customer-app" class="card h-min-600">
    <div class="card-body">
      @hook('admin.rmas.index.content.before')
      <div class="mb-2">{{ __('admin/rma.rma_list_title') }}</div>
      @if (count($rmas))
        <div class="table-push">
          <table class="table table-hover">
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
                <th>{{ __('common.created_at') }}</th>
                @hook('admin.rmas.index.table.headers')
              </tr>
            </thead>
            <tbody>
              @if (count($rmas_format))
                @foreach ($rmas_format as $rma)
                  <tr class="cursor-pointer row-link" data-to-url="{{ admin_route('rmas.show', [$rma['id'], http_build_query(request()->query())]) }}">
                    <td>{{ $rma['name'] }}</td>
                    <td>{{ $rma['email'] }}</td>
                    <td>{{ $rma['telephone'] }}</td>
                    <td>{{ $rma['product_name'] }}</td>
                    <td>{{ $rma['sku'] }}</td>
                    <td>{{ $rma['quantity'] }}</td>
                    <td>{{ $rma['type_text'] }}</td>
                    <td>{{ $rma['status'] }}</td>
                    <td>{{ $rma['created_at'] }}</td>
                    @hook('admin.rmas.index.table.body', $rma)
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
        </div>
        {{ $rmas->links('admin::vendor/pagination/bootstrap-4') }}
      @else
        <x-admin-no-data />
      @endif
      @hook('admin.rmas.index.content.after')
    </div>
  </div>
@endsection
