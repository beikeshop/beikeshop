@extends('admin::layouts.master')

@section('title', __('admin/marketing.marketing_list'))

@section('body-class', 'page-marketing')

@section('content')
  <div id="app" class="card h-min-600" v-cloak>
    <div class="card-body">
      @if (session()->has('errors'))
        <div class="alert alert-danger alert-dismissible d-flex align-items-center" id="error_alert">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <div class="ms-2">
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      <div class="bg-light p-4 mb-4">
        <div class="row">
          <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
            <label class="filter-title">{{ __('product.name') }}</label>
            <input @keyup.enter="search" type="text" v-model="filter.keyword" class="form-control" placeholder="name">
          </div>
          <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
            <label class="filter-title">{{ __('admin/tax_rate.type') }}</label>
            <select v-model="filter.type" class="form-control">
              <option value="">{{ __('common.all') }}</option>
              @foreach ($types as $type)
                <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="row">
          <label class="filter-title"></label>
          <div class="col-auto">
            <button type="button" @click="search"
              class="btn btn-outline-primary btn-sm">{{ __('common.filter') }}</button>
            <button type="button" @click="resetSearch"
              class="btn btn-outline-secondary btn-sm">{{ __('common.reset') }}</button>
          </div>
        </div>
      </div>

      <div class="marketing-wrap" v-if="plugins.data.length">
        <div class="row">
          <div class="col-xl-3 col-md-4 col-6" v-for="plugin, index in plugins.data" :key="index">
            <div class="card mb-4 marketing-item">
              <div class="card-body">
                <div class="plugin-img mb-3"><a :href="'admin/marketing/' + plugin.code"><img :src="plugin.icon_big"
                      class="img-fluid"></a></div>
                <div class="plugin-name fw-bold mb-2">@{{ plugin.name }}</div>
                <div class="d-flex align-items-center justify-content-between">
                  <span class="text-success">免费</span>
                  <span class="text-secondary">下载数：@{{ plugin.downloaded }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-else>
        <x-admin-no-data />
      </div>

      <el-pagination v-if="plugins.data.length" layout="prev, pager, next" background :page-size="plugins.meta.per_page"
        :current-page.sync="page" :total="plugins.meta.total"></el-pagination>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#app',

      data: {
        plugins: @json($plugins ?? []),
        page: 1,

        filter: {
          keyword: bk.getQueryString('keyword'),
          type: bk.getQueryString('type'),
        },
      },

      computed: {
        url: function() {
          let filter = {};
          // if (this.orderBy != 'products.id:desc') {
          //   filter.order_by = this.orderBy;
          // }

          if (this.page > 1) {
            filter.page = this.page;
          }

          for (key in this.filter) {
            const value = this.filter[key];
            if (value !== '' && value !== null) {
              filter[key] = value;
            }
          }

          const query = Object.keys(filter).map(key => key + '=' + filter[key]).join('&');

          const url = @json(admin_route('marketing.index'));

          if (query) {
            return url + '?' + query;
          }

          return url;
        },
      },

      watch: {
        page: function() {
          this.loadData();
        },
      },

      methods: {
        loadData() {
          window.history.pushState('', '', this.url);
          $http.get(this.url).then((res) => {
            this.plugins = res.data.plugins;
          })
        },

        search: function() {
          this.page = 1;
          this.loadData();
        },

        resetSearch() {
          Object.keys(this.filter).forEach(key => this.filter[key] = '')
          this.loadData();
        },
      }
    })
  </script>
@endpush
