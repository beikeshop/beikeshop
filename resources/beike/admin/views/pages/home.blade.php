@extends('admin::layouts.master')

@section('title', '后台管理')

@push('header')
  <script src="{{ asset('vendor/chart/chart.min.js') }}"></script>
@endpush

@section('content')
  <div class="row">
    <div class="col-lg-3 col-6">
      <div class="card mb-4">
        {{-- <div class="card-header">产品总数</div> --}}
        <div class="card-body">
          <h6 class="text-uppercase text-black-50 mb-3">产品总数</h6>
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="fs-2 lh-1 fw-bold">90</div>
              {{-- <span class="mt-n1 ms-2 badge bg-success-soft">+3.5%</span> --}}
            </div>
            <div><i class="fs-4 bi bi-cart"></i></div>
          </div>
          <div class="mt-3 d-flex align-items-center lh-1"><span class="text-success">-24</span> <span class="vr mx-2"></span> <span class="text-muted">较以往新增</span></div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="card mb-4">
        {{-- <div class="card-header">订单总数</div> --}}
        <div class="card-body">
          <h6 class="text-uppercase text-black-50 mb-3">订单总数</h6>
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="fs-2 lh-1 fw-bold">190</div>
            </div>
            <div><i class="fs-4 bi bi-journal-text"></i></div>
          </div>
          <div class="mt-3 d-flex align-items-center lh-1"><span class="text-success">-21%</span> <span class="vr mx-2"></span> <span class="text-muted">较以往新增</span></div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="card mb-4">
        {{-- <div class="card-header">会员总数</div> --}}
        <div class="card-body">
          <h6 class="text-uppercase text-black-50 mb-3">会员总数</h6>
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="fs-2 lh-1 fw-bold">190</div>
            </div>
            <div><i class="fs-4 bi bi-person"></i></div>
          </div>
          <div class="mt-3 d-flex align-items-center lh-1"><span class="text-danger">+30%</span> <span class="vr mx-2"></span> <span class="text-muted">较以往新增</span></div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="card mb-4">
        {{-- <div class="card-header">会员总数</div> --}}
        <div class="card-body">
          <h6 class="text-uppercase text-black-50 mb-3">销售金额</h6>
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="fs-2 lh-1 fw-bold">6666666</div>
            </div>
            <div><i class="fs-4 bi bi-person"></i></div>
          </div>
          <div class="mt-3 d-flex align-items-center lh-1"><span class="text-danger">+140%</span> <span class="vr mx-2"></span> <span class="text-muted">较以往新增</span></div>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header">订单统计</div>
    <div class="card-body">
      <canvas id="orders-chart" style="max-height: 420px"></canvas>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-8 col-12">
      <div class="card mb-4">
        <div class="card-header">客户统计</div>
        <div class="card-body">
          <canvas id="customer-chart" style="max-height: 420px"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-12">
      <div class="card mb-4">
        <div class="card-header">客户统计</div>
        <div class="card-body">
          <canvas id="customer-chart-1" style="max-height: 420px"></canvas>
        </div>
      </div>
    </div>
  </div>


@endsection

@push('footer')
  <script>
  const ctx = document.getElementById('customer-chart').getContext('2d');
  const ctx1 = document.getElementById('customer-chart-1').getContext('2d');
  const orders = document.getElementById('orders-chart').getContext('2d');
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
            // 'rgba(153, 102, 255, 0.2)',
            // 'rgba(255, 159, 64, 0.2)'
        ],
        borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            // 'rgba(153, 102, 255, 1)',
            // 'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          drawBorder: false,
          // ticks: {
          //   display: false,
          // }
          grid: {
            // borderDash: [4],
            drawBorder: false,
          },
        },
        x: {
          beginAtZero: true,
          drawBorder: false,
          grid: {
            drawBorder: false,
            display: false
          },
          // display: false,
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
        // weight: 1,
        // hoverBorderWidth: 5,
        // borderRadius: 5,
        backgroundColor: [
          '#2c7be4',
          '#a5c5f7',
          '#d2ddec',
        ],
        borderWidth: 0
      }]
    }
  });


  const gradient = ctx.createLinearGradient(0, 0, 0, 420);
        gradient.addColorStop(0, 'rgba(180,223,253,1)');
        gradient.addColorStop(1, 'rgba(180,223,253,0)');

  const ordersChart = new Chart(orders, {
    type: 'line',
    data: {
      labels: Array.from({length: 30}, (v, k) => k + 1),
      datasets: [{
        label: ["订单数"],
        backgroundColor : gradient, // Put the gradient here as a fill color
        borderColor : "#4da4f9",
        borderWidth: 2,
        pointColor : "#fff",
        pointStrokeColor : "#ff6c23",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#ff6c23",
        data: Array.from({length: 30}, () => Math.floor(Math.random() * 23.7)),
        fill: true,
        // borderDash: [],
        responsive: true,
        lineTension: 0.3,
        datasetStrokeWidth : 3,
        pointDotStrokeWidth : 4,
        // pointRadius: 3,
      }],
    },
    options: {
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
      },
    }
  });
  </script>
@endpush
