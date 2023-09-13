@extends('admin::layouts.master')

@section('title', __('admin/common.admin_panel'))

@section('body-class', 'admin-home')

@push('header')
  <script src="{{ asset('vendor/chart/chart.min.js') }}"></script>
@endpush

@section('content')
  <div class="row">
    <div class="col-xl-3 col-6">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
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
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
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
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
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
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
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

  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div>{{ __('admin/dashboard.order_report') }}</div>
      <div class="orders-right">
        <div class="btn-group" role="group" aria-label="Basic outlined example">
          <button type="button" class="btn btn-sm btn-outline-info btn-info text-white" data-type="latest_month">{{ __('admin/dashboard.latest_month') }}</button>
          <button type="button" class="btn btn-sm btn-outline-info" data-type="latest_week">{{ __('admin/dashboard.latest_week') }}</button>
          <button type="button" class="btn btn-sm btn-outline-info" data-type="latest_year">{{ __('admin/dashboard.latest_year') }}</button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <canvas id="orders-chart" height="380"></canvas>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    const orders = document.getElementById('orders-chart').getContext('2d');

    const orderGradient = orders.createLinearGradient(0, 0, 0, 380);
          orderGradient.addColorStop(0, 'rgba(180,223,253,1)');
          orderGradient.addColorStop(1, 'rgba(180,223,253,0)');

    const amountGradient = orders.createLinearGradient(0, 0, 0, 380);
          amountGradient.addColorStop(0, 'rgba(32,201,151,0.3)');
          amountGradient.addColorStop(1, 'rgba(32,201,151,0)');

    const latest_month = @json($order_trends['latest_month']);
    const latest_week = @json($order_trends['latest_week']);
    const latest_year = @json($order_trends['latest_year']);

    const ordersChart = new Chart(orders, {
      type: 'line',
      data: {
        // labels: Array.from({length: 30}, (v, k) => k + 1),
        labels: latest_month.period,
        datasets: [
          {
            label: ["{{ __('admin/order.order_quantity') }}"],
            fill: true,
            backgroundColor : orderGradient, // Put the gradient here as a fill color
            borderColor : "#4da4f9",
            borderWidth: 2,
            // data: Array.from({length: 30}, () => Math.floor(Math.random() * 23.7)),
            data: latest_month.totals,
            // borderDash: [],
            responsive: true,
            lineTension: 0.4,
            datasetStrokeWidth: 3,
            pointDotStrokeWidth: 4,
            // pointStyle: 'rect',
            pointHoverBorderWidth: 8,
            // pointBorderColor: [],
            pointBackgroundColor: '#4da4f9',
            // pointColor : "#fff",
            // pointStrokeColor : "#ff6c23",
            // pointHighlightFill: "#fff",
            // pointHighlightStroke: "#ff6c23",
            // pointRadius: 3,
          },
          {
            label: ["{{ __('admin/order.order_amount') }}"],
            fill: true,
            backgroundColor : amountGradient,
            borderColor : "#20c997",
            borderWidth: 2,
            data: latest_month.amounts,
            responsive: true,
            lineTension: 0.4,
            datasetStrokeWidth: 3,
            pointDotStrokeWidth: 4,
            pointHoverBorderWidth: 8,
            pointBackgroundColor: '#20c997',
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: false // Hide legend
        },
        interaction: {
          mode: 'index',
          // axis: 'x',
          intersect: false,
          // includeInvisible: true,
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              drawBorder: false,
              borderDash: [3],
            },
          },
          x: {
            beginAtZero: true,
            grid: {
              drawBorder: false,
              display: false
            },
          }
        },
      }
    });

    function upDate(chart, label, data) {
      chart.data.labels = label;
      data.forEach((e, i) => {
        chart.data.datasets[i].data = e;
      });
      chart.update();
    }

    $('.orders-right .btn-group > .btn').on('click', function() {
      const day = $(this).data('type'); // 天数
      const labels = eval(day).period;
      const data = [eval(day).totals, eval(day).amounts];
      $(this).addClass('btn-info text-white').siblings().removeClass('btn-info text-white');
      upDate(ordersChart, labels, data);
    });
  </script>
@endpush
