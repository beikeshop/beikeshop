@extends('admin::layouts.master')

@section('title', __('admin/common.customer_carts_index'))

@section('content')
  <div class="card h-min-600">
    <div class="card-body">
      <form method="get" action="{{ admin_route('customer_carts.index') }}" class="row g-2 align-items-end mb-4">
        <div class="col-12 col-lg-4">
          <label for="keyword" class="form-label">{{ __('admin/common.customer_carts_keyword') }}</label>
          <input id="keyword" name="keyword" type="search" class="form-control form-control-sm"
                 value="{{ $filters['keyword'] ?? '' }}" maxlength="100">
        </div>
        <div class="col-12 col-sm-4 col-lg-2">
          <label for="visitor" class="form-label">{{ __('admin/common.customer_carts_visitor_type') }}</label>
          <select id="visitor" name="visitor" class="form-select form-select-sm">
            <option value="all" @selected($filters['visitor'] === 'all')>{{ __('common.all') }}</option>
            <option value="customer" @selected($filters['visitor'] === 'customer')>{{ __('admin/common.customer_carts_registered') }}</option>
            <option value="guest" @selected($filters['visitor'] === 'guest')>{{ __('admin/common.customer_carts_guest') }}</option>
          </select>
        </div>
        <div class="col-6 col-sm-4 col-lg-2">
          <label for="from" class="form-label">{{ __('admin/common.customer_carts_from') }}</label>
          <input id="from" name="from" type="date" class="form-control form-control-sm" value="{{ $filters['from'] }}">
        </div>
        <div class="col-6 col-sm-4 col-lg-2">
          <label for="to" class="form-label">{{ __('admin/common.customer_carts_to') }}</label>
          <input id="to" name="to" type="date" class="form-control form-control-sm" value="{{ $filters['to'] }}">
        </div>
        <div class="col-12 col-lg-2 d-flex gap-2">
          <button type="submit" class="btn btn-outline-primary btn-sm">{{ __('common.filter') }}</button>
          <a href="{{ admin_route('customer_carts.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('common.reset') }}</a>
        </div>
      </form>

      @if ($events->count())
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
            <tr>
              <th>{{ __('common.created_at') }}</th>
              <th>{{ __('admin/common.customer') }}</th>
              <th>{{ __('admin/common.customer_carts_product') }}</th>
              <th>{{ __('admin/common.customer_carts_sku') }}</th>
              <th>{{ __('admin/common.customer_carts_quantity') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($events as $event)
              <tr>
                <td class="text-nowrap">{{ $event->created_at }}</td>
                <td>
                  @if ($event->customer)
                    <a href="{{ admin_route('customers.edit', [$event->customer->id]) }}">{{ $event->customer->name }}</a>
                    <div class="small text-muted">{{ $event->customer->email }}</div>
                  @elseif ($event->customer_id)
                    <div>{{ __('admin/common.customer_carts_deleted_customer') }} #{{ $event->customer_id }}</div>
                  @else
                    <div>{{ __('admin/common.customer_carts_guest') }}</div>
                  @endif
                  <div class="small text-muted">{{ __('admin/common.customer_carts_visitor_id') }}: {{ substr($event->visitor_key, 0, 12) }}</div>
                </td>
                <td>
                  <div>{{ $event->product_name ?: __('admin/common.customer_carts_product') }}</div>
                  <div class="small text-muted">#{{ $event->product_id }}</div>
                </td>
                <td>
                  <div>{{ $event->product_sku }}</div>
                  @if ($event->sku_model)
                    <div class="small text-muted">{{ $event->sku_model }}</div>
                  @endif
                </td>
                <td>{{ $event->quantity }}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
        {{ $events->links('admin::vendor/pagination/bootstrap-4') }}
      @else
        <x-admin-no-data/>
      @endif
    </div>
  </div>
@endsection
