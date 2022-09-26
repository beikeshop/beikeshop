@extends('admin::layouts.master')

@section('title', __('admin/marketing.marketing_list'))

@section('body-class', 'page-marketing')

@section('content')
  <div id="app" class="card h-min-600" v-cloak>
    <div class="card-body">
      <div class="bg-light p-4 mb-4">
        <div class="row">
          <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
            <label class="filter-title">{{ __('product.name') }}</label>
            <input @keyup.enter="search" type="text" v-model="filter.name" class="form-control" placeholder="name">
          </div>
          {{-- <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
            <label class="filter-title">{{ __('product.sku') }}</label>
            <input @keyup.enter="search" type="text" v-model="filter.sku" class="form-control" placeholder="sku">
          </div>

          <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
            <label class="filter-title">{{ __('product.model') }}</label>
            <input @keyup.enter="search" type="text" v-model="filter.model" class="form-control" placeholder="model">
          </div> --}}
{{--
          <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
            <label class="filter-title">{{ __('product.category') }}</label>
            <select v-model="filter.category_id" class="form-control">
              <option value="">{{ __('common.all') }}</option>
              @foreach ($categories as $_category)
                <option :value="{{ $_category->id }}">{{ $_category->name }}</option>
              @endforeach
            </select>
          </div> --}}

          <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
            <label class="filter-title">{{ __('common.status') }}</label>
            <select v-model="filter.active" class="form-control">
              <option value="">{{ __('common.all') }}</option>
              <option value="1">{{ __('product.active') }}</option>
              <option value="0">{{ __('product.inactive') }}</option>
            </select>
          </div>
        </div>

        <div class="row">
          <label class="filter-title"></label>
          <div class="col-auto">
            <button type="button" @click="search" class="btn btn-outline-primary btn-sm">{{ __('common.filter') }}</button>
            <button type="button" @click="resetSearch" class="btn btn-outline-secondary btn-sm">{{ __('common.reset') }}</button>
          </div>
        </div>
      </div>

      <div class="marketing-wrap">
        <div class="row">
          <div class="col-xl-3 col-md-4 col-6" v-for="plugin, index in plugins.data" :key="index">
            <div class="card mb-4 marketing-item">
              <div class="card-body">
                <div class="plugin-img mb-3"><a href=""><img :src="plugin.icon_big" class="img-fluid"></a></div>
                <div class="plugin-name fw-bold mb-2">@{{ plugin.name }}</div>
                <div class="d-flex align-items-center justify-content-between">
                  <span class="text-success">免费</span>
                  <span class="text-secondary">下载数：999</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <el-pagination v-if="plugins.data.length" layout="prev, pager, next" background :page-size="plugins.per_page" :current-page.sync="page"
        :total="plugins.total"></el-pagination>
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
        name: bk.getQueryString('name'),
        category_id: bk.getQueryString('category_id'),
        sku: bk.getQueryString('sku'),
        model: bk.getQueryString('model'),
        active: bk.getQueryString('active'),
      },
    },

    watch: {
      page: function() {
        this.loadData();
      },
    },

    methods: {
      loadData() {
        $http.get(`marketing?page=${this.page}`).then((res) => {
          this.marketing = res.data.marketing;
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
