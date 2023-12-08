@extends('admin::layouts.master')

@section('title', __('admin/report_sale.text_report'))

@section('body-class', 'page-pages-form')

@push('header')
  <script src="{{ asset('vendor/chart/chart.min.js') }}"></script>
@endpush

@section('content')
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div>{{ __('admin/report.reports_view_chart') }}</div>
      <div class="orders-right">
        <div class="btn-group" role="group" aria-label="Basic outlined example">
          <button type="button" class="btn btn-sm btn-outline-info btn-info text-white" data-type="latest_month">{{ __('admin/dashboard.latest_month') }}</button>
          <button type="button" class="btn btn-sm btn-outline-info" data-type="latest_week">{{ __('admin/dashboard.latest_week') }}</button>
          <button type="button" class="btn btn-sm btn-outline-info" data-type="latest_year">{{ __('admin/dashboard.latest_year') }}</button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <canvas id="orders-chart" height="400"></canvas>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div>{{ __('admin/report.reports_view') }}</div>
    </div>
    <div class="card-body">
      <table class="table table-hover table-ranking-list">
        <thead>
          <tr>
            <th width="54">ID</th>
            <th>{{ __('admin/common.product') }}</th>
            <th>{{ __('admin/report.view_count') }}</th>
            <th>{{ __('common.created_at') }}</th>
            <th>{{ __('common.action') }}</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($views as $item)
          <tr>
            <td>{{ $item->description->product_id }}</td>
            <td>{{ $item->description->name }}</td>
            <td>{{ $item->view_count }}</td>
            <td>{{ $item->description->created_at }}</td>
            <td><button type="button" class="btn btn-sm btn-outline-secondary view-product-chart" data-id="{{ $item->description->product_id }}">{{ __('admin/report.view_product_chart') }}</button></td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

@push('footer')
<script>
  let viewsTrends = @json($views_trends ?? []);
  const orders = document.getElementById('orders-chart').getContext('2d');

  const orderGradient = orders.createLinearGradient(0, 0, 0, 380);
        orderGradient.addColorStop(0, 'rgba(180,223,253,1)');
        orderGradient.addColorStop(1, 'rgba(180,223,253,0)');

  const amountGradient = orders.createLinearGradient(0, 0, 0, 380);
        amountGradient.addColorStop(0, 'rgba(32,201,151,0.3)');
        amountGradient.addColorStop(1, 'rgba(32,201,151,0)');

  let latest_month = viewsTrends.latest_month;
  let latest_week = viewsTrends.latest_week;
  let latest_year = viewsTrends.latest_year;

  const ordersChart = new Chart(orders, {
    type: 'line',
    data: {
      // labels: Array.from({length: 30}, (v, k) => k + 1),
      labels: latest_month.period,
      datasets: [
        {
          label: ["{{ __('admin/report.view_count') }}"],
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

      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        // legend: false // Hide legend
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
    const labels = Object.keys(eval(day).totals);
    const data = [eval(day).totals];
    $(this).addClass('btn-info text-white').siblings().removeClass('btn-info text-white');
    upDate(ordersChart, labels, data);
  });

  $('.view-product-chart').on('click', function() {
    const id = $(this).data('id');

    $http.get(`reports/product_view/${id}`).then(function(res) {
      // 页面滚动到顶部
      $('#content').animate({scrollTop: 0}, 200);
      viewsTrends = res.data.views_trends;
      ordersChart.data.labels = Object.keys(viewsTrends.latest_month.totals);
      ordersChart.data.datasets[0].data = viewsTrends.latest_month.totals;
      // 同时更新  latest_month, latest_week, latest_year
      latest_month = viewsTrends.latest_month;
      latest_week = viewsTrends.latest_week;
      latest_year = viewsTrends.latest_year;
      // 更新按钮状态
      $('.orders-right .btn-group > .btn').removeClass('btn-info text-white');
      $('.orders-right .btn-group > .btn[data-type="latest_month"]').addClass('btn-info text-white');

      ordersChart.update();
    });
  });
</script>
@endpush



