@extends('admin::layouts.master')

@section('title', __('admin/common.admin_panel'))

@push('header')
  <script src="{{ asset('vendor/chart/chart.min.js') }}"></script>
@endpush

@section('content')
  <div class="row">
    <div class="col-xl-3 col-6">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <span>{{ __('admin/dashboard.customer_view') }}</span>
          <span class="mt-n1 ms-2 badge bg-success-soft">{{ __('admin/dashboard.yesterday') }}</span>
        </div>
        <div class="card-body">
          {{-- <h6 class="text-uppercase text-black-50 mb-3">{{ __('admin/dashboard.customer_view') }}</h6> --}}
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="fs-2 lh-1 fw-bold">{{ $views['total'] }}</div>
            </div>
            {{-- <div><i class="fs-4 bi bi-cart"></i></div> --}}
          </div>
          <div class="mt-3 d-flex align-items-center lh-1"><span class="text-success">{{ $views['percentage'] }}%</span> <span class="vr mx-2"></span> <span class="text-muted">{{ __('admin/dashboard.day_before') }}</span></div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-6">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <span>{{ __('admin/dashboard.order_total') }}</span>
          <span class="mt-n1 ms-2 badge bg-success-soft">{{ __('admin/dashboard.yesterday') }}</span>
        </div>
        <div class="card-body">
          {{-- <h6 class="text-uppercase text-black-50 mb-3">{{ __('admin/dashboard.order_total') }}</h6> --}}
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="fs-2 lh-1 fw-bold">{{ $orders['total'] }}</div>
            </div>
            {{-- <div><i class="fs-4 bi bi-journal-text"></i></div> --}}
          </div>
          <div class="mt-3 d-flex align-items-center lh-1"><span class="text-success">{{ $orders['percentage'] }}%</span> <span class="vr mx-2"></span> <span class="text-muted">{{ __('admin/dashboard.day_before') }}</span></div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-6">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <span>{{ __('admin/dashboard.customer_new') }}</span>
          <span class="mt-n1 ms-2 badge bg-success-soft">{{ __('admin/dashboard.yesterday') }}</span>
        </div>
        <div class="card-body">
          {{-- <h6 class="text-uppercase text-black-50 mb-3">{{ __('admin/dashboard.customer_new') }}</h6> --}}
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="fs-2 lh-1 fw-bold">{{ $customers['total'] }}</div>
            </div>
            {{-- <div><i class="fs-4 bi bi-person"></i></div> --}}
          </div>
          <div class="mt-3 d-flex align-items-center lh-1"><span class="text-danger">{{ $customers['percentage'] }}%</span> <span class="vr mx-2"></span> <span class="text-muted">{{ __('admin/dashboard.day_before') }}</span></div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-6">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <span>{{ __('admin/dashboard.order_amount') }}</span>
          <span class="mt-n1 ms-2 badge bg-success-soft">{{ __('admin/dashboard.yesterday') }}</span>
        </div>
        <div class="card-body">
          {{-- <h6 class="text-uppercase text-black-50 mb-3">{{ __('admin/dashboard.order_amount') }}</h6> --}}
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <img src="https://beikeshop.com/install/install-enter.jpg?version={{ config('beike.version') }}&build_date={{ config('beike.build') }}" class="d-none">
              <div class="fs-2 lh-1 fw-bold">{{ $order_totals['total'] }}</div>
            </div>
            {{-- <div><i class="fs-4 bi bi-person"></i></div> --}}
          </div>
          <div class="mt-3 d-flex align-items-center lh-1"><span class="text-danger">{{ $order_totals['percentage'] }}%</span> <span class="vr mx-2"></span> <span class="text-muted">{{ __('admin/dashboard.day_before') }}</span></div>
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

  @if (0)
  <div class="row">
    <div class="col-xl-8 col-12">
      <div class="card mb-4">
        <div class="card-header">客户统计</div>
        <div class="card-body">
          <canvas id="customer-chart" height="380"></canvas>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-12">
      <div class="card mb-4">
        <div class="card-header">注册来源</div>
        <div class="card-body">
          <canvas id="customer-chart-1" height="380"></canvas>
        </div>
      </div>
    </div>
  </div>
  @endif


@endsection

@push('footer')
  <script>
    const orders = document.getElementById('orders-chart').getContext('2d');

    @if (0)
    const ctx = document.getElementById('customer-chart').getContext('2d');
    const ctx1 = document.getElementById('customer-chart-1').getContext('2d');
    const myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['新注册', '最近访问', '活跃用户', '近期下单'],
        datasets: [{
          label: '# of Votes',
          // backgroundColor: '#ffadb9',
          // borderColor: '#f7072b',
          // barThickness: 30,
          // borderRadius: 20, // This will round the corners
          // borderSkipped: false, // To make all side rounded
          data: [12, 19, 3, 5],
          // borderRadius: 5,
          backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
          ],
          borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              drawBorder: false,
            },
          },
          x: {
            beginAtZero: true,
            grid: {
              drawBorder: false,
              display: false
            },
          }
        }
      }
    });

    const myChart1 = new Chart(ctx1, {
      type: 'doughnut',
      data: {
        labels: ['Red', 'Orange', 'Yellow'],
        datasets: [{
          label: '# of Votes',
          data: [112, 19, 3],
          cutout: '80%',
          radius: '80%',
          spacing: 6,
          hoverOffset: 4,
          backgroundColor: ['#2c7be4','#a5c5f7','#d2ddec',],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    });
    @endif


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
              // drawOnChartArea: false,
              display: false
            },
          }
        },
      }
    });
    // console.log(ordersChart)

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
      // const labels = Array.from({length: day}, (v, k) => k + 1);
      // const data = Array.from({length: day}, () => Math.floor(Math.random() * 123.7));
      $(this).addClass('btn-info text-white').siblings().removeClass('btn-info text-white');

      upDate(ordersChart, labels, data);
    });
  </script>
@endpush
