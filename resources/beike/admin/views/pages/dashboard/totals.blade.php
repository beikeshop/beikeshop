<div class="row g-lg-4 g-2 mb-4">
  <div class="col-xl-3 col-6">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ __('admin/dashboard.order_amount') }}</span>
        <span class="mt-n1 ms-2 badge bg-success-soft">{{ __('admin/dashboard.today') }}</span>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <img src="https://beikeshop.com/install/install-enter.jpg?version={{ config('beike.version') }}&build_date={{ config('beike.build') }}" class="d-none">
            <div class="fs-2 lh-1 fw-bold">{{ $order_totals['today'] }}</div>
          </div>
        </div>
        <div class="mt-3 d-flex align-items-center lh-1">
          <span class="text-muted me-1">{{ __('admin/dashboard.yesterday') }}</span>
          <span class="text-{{ $order_totals['yesterday'] >= 0 ? 'success' : 'danger' }}">{{ $order_totals['yesterday'] }}</span>
          <span class="vr mx-2"></span>
          <span class="text-muted me-1">{{ __('admin/dashboard.day_before') }}</span>
          <span class="text-{{ $order_totals['percentage'] >= 0 ? 'success' : 'danger' }}">{{ $order_totals['percentage'] }}%</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-6">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ __('admin/dashboard.order_total') }}</span>
        <span class="mt-n1 ms-2 badge bg-success-soft">{{ __('admin/dashboard.today') }}</span>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="fs-2 lh-1 fw-bold">{{ $orders['today'] }}</div>
          </div>
        </div>
        <div class="mt-3 d-flex align-items-center lh-1">
          <span class="text-muted me-1">{{ __('admin/dashboard.yesterday') }}</span>
          <span class="text-{{ $orders['yesterday'] >= 0 ? 'success' : 'danger' }}">{{ $orders['yesterday'] }}</span>
          <span class="vr mx-2"></span>
          <span class="text-muted me-1">{{ __('admin/dashboard.day_before') }}</span>
          <span class="text-{{ $orders['percentage'] >= 0 ? 'success' : 'danger' }}">{{ $orders['percentage'] }}%</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-6">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ __('admin/dashboard.customer_new') }}</span>
        <span class="mt-n1 ms-2 badge bg-success-soft">{{ __('admin/dashboard.today') }}</span>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="fs-2 lh-1 fw-bold">{{ $customers['today'] }}</div>
          </div>
        </div>
        <div class="mt-3 d-flex align-items-center lh-1">
          <span class="text-muted me-1">{{ __('admin/dashboard.yesterday') }}</span>
          <span class="text-{{ $customers['yesterday'] >= 0 ? 'success' : 'danger' }}">{{ $customers['yesterday'] }}</span>
          <span class="vr mx-2"></span>
          <span class="text-muted me-1">{{ __('admin/dashboard.day_before') }}</span>
          <span class="text-{{ $customers['percentage'] >= 0 ? 'success' : 'danger' }}">{{ $customers['percentage'] }}%</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-6">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ __('admin/dashboard.product_total') }}</span>
        <span class="mt-n1 ms-2 badge bg-success-soft">{{ __('admin/dashboard.today') }}</span>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="fs-2 lh-1 fw-bold">{{ $products['today'] }}</div>
          </div>
        </div>
        <div class="mt-3 d-flex align-items-center lh-1">
          <span class="text-muted me-1">{{ __('admin/dashboard.yesterday') }}</span>
          <span class="text-{{ $products['yesterday'] >= 0 ? 'success' : 'danger' }}">{{ $products['yesterday'] }}</span>
          <span class="vr mx-2"></span>
          <span class="text-muted me-1">{{ __('admin/dashboard.day_before') }}</span>
          <span class="text-{{ $products['percentage'] >= 0 ? 'success' : 'danger' }}">{{ $products['percentage'] }}%</span>
        </div>
      </div>
    </div>
  </div>
</div>