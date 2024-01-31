@extends('admin::layouts.master')

@section('title', __('admin/report_sale.text_report'))

@section('body-class', 'page-pages-form')

@push('header')
  <script src="{{ asset('vendor/chart/chart.min.js') }}"></script>
@endpush

@section('content')
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div>{{ __('admin/dashboard.order_report') }}</div>
      <div class="orders-right">
        <div class="btn-group" role="group" aria-label="Basic outlined example">
          <button type="button" class="btn btn-sm btn-outline-info btn-info text-white" data-type="latest_month">{{ __('admin/dashboard.latest_month') }}</button>
          <button type="button" class="btn btn-sm btn-outline-info" data-type="latest_year">{{ __('admin/dashboard.latest_year') }}</button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="bg-light p-2 mb-3" id="app">
        <el-form :inline="true" ref="filterForm" :model="filter" class="demo-form-inline" label-width="100px">
          <el-form-item label="{{ __('common.status') }}" class="mb-0">
            <el-select v-model="filter.statuses" multiple placeholder="请选择" size="small" class="wp-400" @change="changeSearch" @visible-change="search">
              <el-option v-for="item in statuses" :key="item.status" :label="item.name" :value="item.status"></el-option>
            </el-select>
          </el-form-item>
        </el-form>
      </div>

      <div><canvas id="orders-chart" height="400"></canvas></div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div>{{ __('admin/report_sale.quantity_by_products') }}</div>
        </div>
        <div class="card-body">
          <table class="table table-hover table-ranking-list">
            <thead>
              <tr>
                <th class="text-center" width="74">{{ __('admin/report_sale.text_ranking') }}</th>
                <th>{{ __('admin/common.product') }}</th>
                <th>{{ __('common.sales') }}</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($quantity_by_products as $item)
              <tr>
                <td class="text-center">
                  @if ($loop->iteration <= 3)
                    <img src="{{ asset('image/ranking/ranking_'.$loop->iteration.'.png') }}" class="img-fluid ranking-icon">
                  @else
                    {{ $loop->iteration }}
                  @endif
                </td>
                <td><a target="_blank" href="{{ admin_route('products.edit', [$item['product_id']]) }}" class="text-link text-break">{{ $item['product']['description']['name'] ?? 'NONE' }}</a></td>
                <td>{{ $item['total_quantity'] }}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div>{{ __('admin/report_sale.amount_by_products') }}</div>
        </div>
        <div class="card-body">
          <table class="table table-hover table-ranking-list">
            <thead>
              <tr>
                <th class="text-center" width="74">{{ __('admin/report_sale.text_ranking') }}</th>
                <th>{{ __('admin/common.product') }}</th>
                <th>{{ __('shop/account.amount') }}</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($amount_by_products as $item)
              <tr>
                <td class="text-center">
                  @if ($loop->iteration <= 3)
                    <img src="{{ asset('image/ranking/ranking_'.$loop->iteration.'.png') }}" class="img-fluid ranking-icon">
                  @else
                    {{ $loop->iteration }}
                  @endif
                </td>
                <td><a target="_blank" href="{{ admin_route('products.edit', [$item['product_id']]) }}" class="text-link text-break">{{ $item['product']['description']['name'] ?? 'NONE' }}</a></td>
                <td>{{ $item['total_amount'] }}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div>{{ __('admin/report_sale.amount_by_customers') }}</div>
        </div>
        <div class="card-body">
          {{-- {{dd($amount_by_customers)}} --}}
          <table class="table table-hover table-ranking-list">
            <thead>
              <tr>
                <th class="text-center" width="74">{{ __('admin/report_sale.text_ranking') }}</th>
                <th>{{ __('admin/customer.user_name') }}</th>
                <th>{{ __('shop/account.amount') }}</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($amount_by_customers as $item)
              <tr>
                <td class="text-center">
                  @if ($loop->iteration <= 3)
                    <img src="{{ asset('image/ranking/ranking_'.$loop->iteration.'.png') }}" class="img-fluid ranking-icon">
                  @else
                    {{ $loop->iteration }}
                  @endif
                </td>
                <td><a target="_blank" href="{{ admin_route('customers.edit', [$item['customer']['id'] ?? 0]) }}" class="text-link text-break">{{ $item['customer']['name'] ?? '' }}</a></td>
                <td>{{ $item['order_amount'] }}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
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

  let app = new Vue({
    el: '#app',
    data: {
      url: '{{ admin_route("reports_sale.index") }}',
      statuses: @json($statuses),
      isStatusOpen: false,
      filter: {
        statuses: @json($statuses_selected ?? []),
        start: bk.getQueryString('start'),
        end: bk.getQueryString('end'),
      },
    },

    watch: {
      "filter.start": {
        handler(newVal,oldVal) {
          if(!newVal) {
            this.filter.start = ''
          }
        }
      },
      "filter.end": {
        handler(newVal,oldVal) {
          if(!newVal) {
            this.filter.end = ''
          }
        }
      }
    },

    methods: {
      pickerDate(type) {
        if(this.filter.end && this.filter.start > this.filter.end) {
            if(type) {
            this.filter.start = ''
          } else {
            this.filter.end = ''
          }
        }
      },

      search(e) {
        this.isStatusOpen = e
        if (!e) {
          location = bk.objectToUrlParams(this.filter, this.url)
        }
      },

      changeSearch(e) {
        if (!this.isStatusOpen) {
          location = bk.objectToUrlParams(this.filter, this.url)
        }
      },

      resetSearch() {
        this.filter = bk.clearObjectValue(this.filter)
        location = bk.objectToUrlParams(this.filter, this.url)
      },
    }
  });
</script>
@endpush



