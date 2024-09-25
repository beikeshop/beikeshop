@extends('admin::layouts.master')

@section('title', __('admin/marketing.marketing_list'))


@section('body-class', 'page-marketing')

@section('page-title-after')
<div class="d-flex">
  <div class="text-nowrap me-2">{{ __('admin/marketing.attention_1') }}</div>
  <div class="top-text">
    {{ __('admin/marketing.attention_2') }}
    <br>
    {{ __('admin/marketing.attention_3') }}
  </div>
</div>
@endsection

@section('page-title-right')
  <button type="button" class="btn btn-outline-info set-token" onclick="app.setToken()">{{ __('admin/marketing.set_token') }}</button>
  <a href="{{ beike_api_url() }}/account/plugins/create" class="btn btn-outline-info" target="_blank">我要发布</a>
@endsection

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

      <template v-if="plugins">
        <div class="bg-light p-4 mb-4">
          <div class="row">
            <div class="col-xxl-3 col-lg-4 col-sm-6 d-flex align-items-center mb-0 search-wrap">
              <input @keyup.enter="search" type="text" v-model="filter.keyword" class="form-control" placeholder="{{ __('admin/marketing.plugin_name') }}">
              <button type="button" @click="search"
                class="btn btn-primary">{{ __('admin/builder.text_search') }}</button>
            </div>
            <div class="col-xxl-3 col-lg-4 col-sm-6 d-flex align-items-center mb-0">
              {{-- <label class="filter-title">{{ __('admin/plugin.plugin_type') }}</label> --}}
              <select v-model="filter.type" class="form-select search-category-wrap" @change="search">
                <option value="">{{ __('common.all') }}{{ __('admin/plugin.plugin_type') }}</option>
                @foreach ($types as $type)
                  <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- <div class="row">
            <label class="filter-title"></label>
            <div class="col-auto">
              <button type="button" @click="search"
                class="btn btn-outline-primary btn-sm">{{ __('admin/builder.text_search') }}</button>
              <button type="button" @click="resetSearch"
                class="btn btn-outline-secondary btn-sm ms-1">{{ __('common.reset') }}</button>
            </div>
          </div> --}}
        </div>
        <div class="marketing-wrap" v-if="plugins.data.length">
          <div class="row">
            <div class="col-xxl-20 col-xl-3 col-md-4 col-6" v-for="plugin, index in plugins.data" :key="index">
              <div class="card mb-4 marketing-item">
                <div class="card-body">
                  <div class="plugin-img mb-3">
                    <div class="sale-wrap" v-if="plugin.origin_price"><img :src="plugin.sale_icon" v-if="plugin.sale_icon" class="img-fluid"></div>
                    <a :href="'{{ system_setting('base.admin_name', 'admin') }}/marketing/' + plugin.code"><img :src="plugin.icon_big"
                        class="img-fluid"></a>
                  </div>
                  <div class="plugin-name fw-bold mb-2">@{{ plugin.name }}</div>
                  <div class="d-flex align-items-center">
                    <span class="text-success fs-5" v-if="plugin.price == 0">{{ __('admin/marketing.text_free') }}</span>
                    <span class="text-success fs-5" v-else>@{{ plugin.price_format }}</span>
                    <span v-if="plugin.origin_price" class="text-decoration-line-through text-secondary ms-2">@{{ plugin.origin_price_format }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-else>
          <x-admin-no-data />
        </div>

        <el-pagination v-if="plugins.data.length" layout="total, prev, pager, next" background :page-size="plugins.meta.per_page"
          :current-page.sync="page" :total="plugins.meta.total"></el-pagination>
      </template>
      <template v-else>
        <div class="cart">
          <div class="cart-body">
            <div class="alert alert-warning py-3" role="alert">
              <h4 class="alert-heading">{{ __('admin/marketing.data_request_error') }}</h4>
              <ol class="ps-3">
                <li>{{ __('admin/marketing.request_error_1') }}</li>
                <li>{{ __('admin/marketing.request_error_2') }}</li>
              </ol>
              <hr>
              <p class="mb-0">
                {{ __('admin/marketing.request_error_text') }}
                <a href="https://beikeshop.cn/account/tickets/create" target="_blank">{{ __('admin/marketing.submit_work_order') }} <i class="bi bi-box-arrow-up-right"></i></a>
              </p>
            </div>
          </div>
        </div>
      </template>
    </div>

    <el-dialog
      title="{{ __('admin/marketing.set_token') }}"
      :close-on-click-modal="false"
      :visible.sync="setTokenDialog.show"
      width="500px">
      <el-input
        type="textarea"
        :rows="3"
        placeholder="{{ __('admin/marketing.set_token') }}"
        v-model="setTokenDialog.token">
      </el-input>
      <div class="mt-3 text-secondary fs-6">{{ __('admin/marketing.get_token_text') }} <a href="{{ beike_api_url() }}/account/websites?domain={{ $domain }}" class="link-primary" target="_blank">{{ __('admin/marketing.get_token') }}</a></div>
      <div class="d-flex justify-content-end align-items-center mt-4">
        <span slot="footer" class="dialog-footer">
          <el-button @click="setTokenDialog.show = false">{{ __('common.cancel') }}</el-button>
          <el-button type="primary" @click="submitToken">{{ __('common.confirm') }}</el-button>
        </span>
      </div>
    </el-dialog>
  </div>
@endsection

@push('footer')
  <script>
    let app = new Vue({
      el: '#app',

      data: {
        plugins: @json($plugins ?? null),
        same_domain: @json($same_domain ?? false),
        page: bk.getQueryString('page', 1) * 1,
        isBack: false,

        filter: {
          keyword: bk.getQueryString('keyword'),
          type: bk.getQueryString('type'),
        },

        setTokenDialog: {
          show: false,
          token: @json(system_setting('base.developer_token') ?? ''),
        }
      },

      mounted() {
        // 监听浏览器返回事件
        window.addEventListener('popstate', () => {
          const page = bk.getQueryString('page');
          this.isBack = true;

          if (this.page < 2) {
            window.history.back(-1);
            return;
          }

          this.page = page * 1;
          this.loadData(true);
        });
        this.getToken()
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

          Object.keys(this.filter).forEach(key => {
            const value = this.filter[key];
            if (value !== '' && value !== null) {
              filter[key] = value;
            }
          })

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
          if (!this.isBack) {
            window.history.pushState('', '', this.url);
          }
          $http.get(this.url).then((res) => {
            this.plugins = res.data.plugins;
            this.isBack = false;
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

        setToken() {
          if (!this.same_domain) {
            layer.alert('{{ __('admin/marketing.same_domain_error') }}', {icon: 2, area: ['400px'], btn: ['{{ __('common.confirm') }}'], title: '{{__("common.text_hint")}}'});
            return;
          }

          this.setTokenDialog.show = true;
        },

        getToken() {
          if (this.setTokenDialog.token) {
            return;
          }

          $http.get(`{{ admin_route('marketing.get_token') }}?domain=${config.app_url}`, null, {hload: true}).then((res) => {
            if (res.status == 'success') {
              if (res.data.data) {
                this.setTokenDialog.token = res.data.data;
                $http.post('{{ admin_route('settings.store_token') }}', {developer_token: res.data.data})
              }
            }
          })
        },

        submitToken() {
          if (!this.setTokenDialog.token) {
            return;
          }

          $http.get(`${config.api_url}/api/website/check_token`, {domain: config.app_url, token: this.setTokenDialog.token}).then((res) => {
            if (!res.exist) {
              layer.msg('{{ __('admin/marketing.check_token_error') }}', () => {});
              return;
            }

            $http.post('{{ admin_route('settings.store_token') }}', {developer_token: this.setTokenDialog.token}).then((res) => {
              this.setTokenDialog.show = false;
              layer.msg(res.message)
            })
          })
        }
      }
    })
  </script>
@endpush
