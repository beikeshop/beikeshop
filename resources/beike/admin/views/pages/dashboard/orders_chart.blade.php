@push('header')
  <script src="{{ asset('vendor/chart/chart.min.js') }}"></script>
@endpush

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