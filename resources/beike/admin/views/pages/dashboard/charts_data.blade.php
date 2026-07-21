@push('header')
  <script src="{{ asset('vendor/chart/chart.min.js') }}"></script>
@endpush

<br>

@hook('admin.dashboard.charts_data.before')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
  <div>
    <h5 class="font-weight-bold mb-1">{{ __('admin/common.dashboard') }}</h5>
    <p class="text-muted mb-0">{{ __('admin/dashboard.page_title') }}</p>
  </div>

  <div class="d-flex flex-wrap gap-2 mt-3 mt-md-0 time-filter-buttons">
    <div class="btn-group" role="group">
      <button type="button" class="btn btn-primary" id="btn-today">{{ __('admin/dashboard.today') }}</button>
      <button type="button" class="btn btn-default" id="btn-yesterday">{{ __('admin/dashboard.yesterday') }}</button>
      <button type="button" class="btn btn-default" id="btn-week">{{ __('admin/dashboard.near_7_days') }}</button>
    </div>

    <button class="btn btn-default" onclick="bkDashboard.exportReport()">
      <i class="bi bi-box-arrow-in-down"></i>
      {{ __('admin/dashboard.export_report') }}
    </button>

    <button class="btn btn-default" onclick="location.reload()">
      <i class="bi bi-arrow-repeat me-1"></i>
      {{ __('admin/dashboard.refresh_data') }}
    </button>

    @hook('admin.dashboard.charts_data.top.btn.after')
  </div>
</div>

@hook('admin.dashboard.charts_data.top.after')

<div class="row g-3 mb-4">
  @hook('admin.dashboard.charts_data.top.row.before')

  <div class="col-12 col-sm-6 col-lg-3">
    <div class="card rounded-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="dashboard-stat-content">
            <p class="fw-bold text-muted mb-1">{{ __('admin/dashboard.visitor_count') }}</p>
            <h3 class="dashboard-stat-value" id="visitor-count">0</h3>
            <div class="dashboard-stat-change dashboard-change-success">
              <i class="bi bi-arrow-up"></i>
              <span>0</span>
              <span class="dashboard-change-text">{{ __('admin/dashboard.vs_yesterday') }}</span>
            </div>
          </div>
          <div class="dashboard-stat-icon dashboard-icon-primary">
            <i class="bi bi-people-fill"></i>
          </div>
        </div>
        <div class="dashboard-stat-chart">
          <canvas id="visitor-chart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-lg-3">
    <div class="card rounded-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="dashboard-stat-content">
            <p class="fw-bold text-muted mb-1">{{ __('admin/dashboard.cart_count') }}</p>
            <h3 class="dashboard-stat-value" id="cart-count">0</h3>
            <div class="dashboard-stat-change dashboard-change-success">
              <i class="bi bi-arrow-up"></i>
              <span>0</span>
              <span class="dashboard-change-text">{{ __('admin/dashboard.vs_yesterday') }}</span>
            </div>
          </div>
          <div class="dashboard-stat-icon dashboard-icon-warning">
            <i class="bi bi-cart-fill"></i>
          </div>
        </div>
        <div class="dashboard-stat-chart">
          <canvas id="cart-chart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-lg-3">
    <div class="card rounded-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="dashboard-stat-content">
            <p class="fw-bold text-muted mb-1">{{ __('admin/dashboard.purchase_count') }}</p>
            <h3 class="dashboard-stat-value" id="purchase-count">0</h3>
            <div class="dashboard-stat-change dashboard-change-danger">
              <i class="bi bi-arrow-down"></i>
              <span>0</span>
              <span class="dashboard-change-text">{{ __('admin/dashboard.vs_yesterday') }}</span>
            </div>
          </div>
          <div class="dashboard-stat-icon dashboard-icon-success">
            <i class="bi bi-credit-card-2-back-fill"></i>
          </div>
        </div>
        <div class="dashboard-stat-chart">
          <canvas id="purchase-chart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-lg-3">
    <div class="card rounded-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="dashboard-stat-content dashboard-conversion-content">
            <div class="d-flex align-items-center justify-content-between dashboard-conversion-header mb-0 lh-1">
              <p class="fw-bold text-muted mb-0">{{ __('admin/dashboard.conversion_rate') }}</p>
              <div class="dashboard-conversion-select">
                <select id="conversion-type-select" class="dashboard-select">
                  <option value="visitor-to-purchase">{{ __('admin/dashboard.visitor_to_purchase') }}</option>
                  <option value="visitor-to-cart">{{ __('admin/dashboard.visitor_to_cart') }}</option>
                  <option value="cart-to-purchase">{{ __('admin/dashboard.cart_to_purchase') }}</option>
                </select>
                <i class="bi bi-chevron-down dashboard-select-arrow"></i>
              </div>
            </div>
            <h3 class="dashboard-stat-value" id="conversion-rate">0%</h3>
            <div class="dashboard-stat-change dashboard-change-success">
              <i class="bi bi-arrow-up"></i>
              <span id="conversion-change">0%</span>
              <span class="dashboard-change-text">{{ __('admin/dashboard.vs_yesterday') }}</span>
            </div>
          </div>
          <div class="dashboard-stat-icon dashboard-icon-secondary">
            <i class="bi bi-bar-chart-line-fill"></i>
          </div>
        </div>
        <div class="dashboard-stat-chart">
          <canvas id="conversion-chart"></canvas>
        </div>
      </div>
    </div>
  </div>

  @hook('admin.dashboard.charts_data.top.row.after')
</div>

@hook('admin.dashboard.charts_data.pv-uv.before')

<div class="row g-3 mb-4">
  <div class="col-12 col-lg-8">
    @if ($firstLoginAction)
    <img src="https://beikeshop.com/install/install-enter.jpg?version={{ config('beike.version') }}&build_date={{ config('beike.build') }}" class="d-none">
    @endif
    <div class="card rounded-4">
      <div class="card-body">
        <div class="dashboard-chart-header">
          <h3 class="dashboard-chart-title">{{ __('admin/dashboard.visitor_trend') }}</h3>
          <div class="dashboard-chart-buttons">
            <button id="chart-btn-today" class="dashboard-btn dashboard-btn-primary">{{ __('admin/dashboard.today') }}</button>
            <button id="chart-btn-yesterday" class="dashboard-btn dashboard-btn-secondary">{{ __('admin/dashboard.yesterday') }}</button>
            <button id="chart-btn-week" class="dashboard-btn dashboard-btn-secondary">{{ __('admin/dashboard.near_7_days') }}</button>
          </div>
        </div>
        <div class="dashboard-chart-content">
          <canvas id="pv-uv-chart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="card rounded-4">
      <div class="card-body">
        <div class="dashboard-chart-header">
          <h3 class="dashboard-chart-title">{{ __('admin/dashboard.customer_source_analysis') }}</h3>
          <button class="dashboard-chart-menu">
            <i class="bi bi-three-dots-vertical"></i>
          </button>
        </div>
        <div class="dashboard-source-grid">
          <div class="dashboard-source-chart-area">
            <canvas id="source-chart"></canvas>
          </div>
          <div class="dashboard-source-legend-area" id="source-legend-container">

            <div class="text-center py-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">{{ __('admin/dashboard.loading') }}</span>
              </div>
              <p class="text-muted mt-2">{{ __('admin/dashboard.loading_source_data') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@hook('admin.dashboard.charts_data.pv-uv.after')

<div class="row g-3">
  <div class="col-12 col-lg-4">
    <div class="card h-100 rounded-4">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h5 class="font-weight-semibold mb-0">{{ __('admin/dashboard.order_funnel') }}</h5>
          <button id="funnel-refresh-btn" class="btn btn-link btn-sm text-muted p-0" title="{{ __('admin/dashboard.refresh_funnel_data') }}">
            <i class="bi bi-arrow-repeat fs-5"></i>
          </button>
        </div>
        <div style="height: 300px;">
          <canvas id="funnel-chart"></canvas>
        </div>

        <div id="funnel-data-container" class="funnel-data-container" style="display: none;">

        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="card h-100 rounded-4">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h5 class="font-weight-semibold mb-0">{{ __('admin/dashboard.hot_products') }}</h5>
          <div class="dropdown">
            <button id="hot-products-filter-btn" class="btn btn-link btn-sm text-muted p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('admin/dashboard.sort_options') }}">
              <i class="bi bi-funnel-fill"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><h6 class="dropdown-header">{{ __('admin/dashboard.sort_options') }}</h6></li>
              <li><a class="dropdown-item" href="#" data-sort="sales-desc"><i class="bi bi-sort-down fs-5 me-1"></i>{{ __('admin/dashboard.sort_by_sales_desc') }}</a></li>
              <li><a class="dropdown-item" href="#" data-sort="sales-asc"><i class="bi bi-sort-down-alt fs-5 me-1"></i>{{ __('admin/dashboard.sort_by_sales_asc') }}</a></li>
              <li><a class="dropdown-item" href="#" data-sort="price-desc"><i class="bi bi-sort-numeric-down-alt fs-5 me-1"></i>{{ __('admin/dashboard.sort_by_price_desc') }}</a></li>
              <li><a class="dropdown-item" href="#" data-sort="price-asc"><i class="bi bi-sort-numeric-down fs-5 me-1"></i>{{ __('admin/dashboard.sort_by_price_asc') }}</a></li>
            </ul>
          </div>
        </div>
        <div class="dashboard-product-list" id="hot-products-container">

          <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">{{ __('admin/dashboard.loading') }}</span>
            </div>
            <p class="text-muted mt-2">{{ __('admin/dashboard.loading_hot_products') }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="card h-100 rounded-4">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h5 class="font-weight-semibold mb-0">{{ __('admin/dashboard.slow_products') }}</h5>
          <div class="dropdown">
            <button id="slow-products-filter-btn" class="btn btn-link btn-sm text-muted p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('admin/dashboard.sort_options') }}">
              <i class="bi bi-funnel-fill"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><h6 class="dropdown-header">{{ __('admin/dashboard.sort_options') }}</h6></li>
              <li><a class="dropdown-item" href="#" data-sort="sales-asc"><i class="bi bi-sort-down fs-5 me-1"></i>{{ __('admin/dashboard.sort_by_sales_asc') }}</a></li>
              <li><a class="dropdown-item" href="#" data-sort="sales-desc"><i class="bi bi-sort-down-alt fs-5 me-1"></i>{{ __('admin/dashboard.sort_by_sales_desc') }}</a></li>
              <li><a class="dropdown-item" href="#" data-sort="price-desc"><i class="bi bi-sort-numeric-down-alt fs-5 me-1"></i>{{ __('admin/dashboard.sort_by_price_desc') }}</a></li>
              <li><a class="dropdown-item" href="#" data-sort="price-asc"><i class="bi bi-sort-numeric-down fs-5 me-1"></i>{{ __('admin/dashboard.sort_by_price_asc') }}</a></li>
            </ul>
          </div>
        </div>
        <div class="dashboard-product-list" id="slow-products-container">

          <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">{{ __('admin/dashboard.loading') }}</span>
            </div>
            <p class="text-muted mt-2">{{ __('admin/dashboard.loading_slow_products') }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@hook('admin.dashboard.charts_data.bottom.after')


@push('footer')
  <script>
    // Dashboard translations for JavaScript
    window.dashboardTranslations = {
      sales: '{{ __('admin/dashboard.sales') }}',
      direct_access: '{{ __('admin/dashboard.direct_access') }}',
      google: '{{ __('admin/dashboard.google') }}',
      baidu: '{{ __('admin/dashboard.baidu') }}',
      bing: '{{ __('admin/dashboard.bing') }}',
      facebook: '{{ __('admin/dashboard.facebook') }}',
      twitter: '{{ __('admin/dashboard.twitter') }}',
      youtube: '{{ __('admin/dashboard.youtube') }}',
      linkedin: '{{ __('admin/dashboard.linkedin') }}',
      instagram: '{{ __('admin/dashboard.instagram') }}',
      other: '{{ __('admin/dashboard.other') }}',
      no_data: '{{ __('admin/dashboard.no_data') }}',
      // Funnel chart labels
      product_views: '{{ __('admin/dashboard.product_views') }}',
      unique_visitors: '{{ __('admin/dashboard.unique_visitors') }}',
      cart_additions: '{{ __('admin/dashboard.cart_additions') }}',
      orders: '{{ __('admin/dashboard.orders') }}',
      successful_payments: '{{ __('admin/dashboard.successful_payments') }}',
      // Error messages
      get_source_analysis_failed: '{{ __('admin/dashboard.get_source_analysis_failed') }}',
      network_error: '{{ __('admin/dashboard.network_error') }}'
    };

    // Dashboard API routes for JavaScript
    window.dashboardRoutes = {
      stats: '{{ admin_route('dashboard.stats') }}',
      trends: '{{ admin_route('dashboard.trends') }}',
      miniCharts: '{{ admin_route('dashboard.mini-charts') }}',
      source: '{{ admin_route('dashboard.source') }}',
      funnel: '{{ admin_route('dashboard.funnel') }}',
      products: {
        hot: '{{ admin_route('dashboard.products', ['type' => 'hot']) }}',
        slow: '{{ admin_route('dashboard.products', ['type' => 'slow']) }}'
      },
      export: '{{ admin_route('dashboard.export') }}'
    };
  </script>
@endpush
