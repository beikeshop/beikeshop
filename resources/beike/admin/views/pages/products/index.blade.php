@extends('admin::layouts.master')

@section('title', __('admin/common.product'))

@section('content')
  @if ($errors->has('error'))
    <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4" />
  @endif

  @if (session()->has('success'))
    <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4" />
  @endif

  <div id="product-app">
    <div class="card">
      <div class="card-body">
        <div class="bg-light p-4">
          <div class="row">
            <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
              <label class="filter-title">{{ __('product.name') }}</label>
              <input @keyup.enter="search" type="text" v-model="filter.name" class="form-control" placeholder="name">
            </div>
            <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
              <label class="filter-title">{{ __('product.sku') }}</label>
              <input @keyup.enter="search" type="text" v-model="filter.sku" class="form-control" placeholder="sku">
            </div>

            <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
              <label class="filter-title">{{ __('product.model') }}</label>
              <input @keyup.enter="search" type="text" v-model="filter.model" class="form-control" placeholder="model">
            </div>

            <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
              <label class="filter-title">{{ __('product.category') }}</label>
              <select v-model="filter.category_id" class="form-control">
                <option value="">{{ __('common.all') }}</option>
                @foreach ($categories as $_category)
                  <option :value="{{ $_category->id }}">{{ $_category->name }}</option>
                @endforeach
              </select>
            </div>

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

        <div class="d-flex justify-content-between my-4" v-if="product.data.length">
          @if ($type != 'trashed')
            <a href="{{ admin_route('products.create') }}">
              <button class="btn btn-primary">{{ __('admin/product.products_create') }}</button>
            </a>
          @else
            <button class="btn btn-primary" @click="clearRestore">{{ __('admin/product.clear_restore') }}</button>
          @endif

          @if ($type != 'trashed')
          <div class="right">
            <button class="btn btn-outline-secondary" :disabled="!selected.length" @click="batchDelete">{{ __('admin/product.batch_delete')  }}</button>
            <button class="btn btn-outline-secondary" :disabled="!selected.length"
            @click="batchActive(true)">{{ __('admin/product.batch_active') }}</button>
            <button class="btn btn-outline-secondary" :disabled="!selected.length"
            @click="batchActive(false)">{{ __('admin/product.batch_inactive') }}</button>
          </div>
          @endif
        </div>

        <template v-if="product.data.length">
          <table class="table table-hover">
            <thead>
              <tr>
                <th><input type="checkbox" v-model="allSelected" /></th>
                <th>{{ __('common.id') }}</th>
                <th>{{ __('product.image') }}</th>
                <th>{{ __('product.name') }}</th>
                <th>{{ __('product.price') }}</th>
                <th>
                  <div class="d-flex align-items-center">
                      {{ __('common.created_at') }}
                    <div class="d-flex flex-column ml-1 order-by-wrap">
                      <i class="el-icon-caret-top" @click="orderBy = 'created_at:asc'"></i>
                      <i class="el-icon-caret-bottom" @click="orderBy = 'created_at:desc'"></i>
                    </div>
                  </div>
                </th>

                <th class="d-flex align-items-center">
                  <div class="d-flex align-items-center">
                      {{ __('common.sort_order') }}
                    <div class="d-flex flex-column ml-1 order-by-wrap">
                      <i class="el-icon-caret-top" @click="orderBy = 'position:asc'"></i>
                      <i class="el-icon-caret-bottom" @click="orderBy = 'position:desc'"></i>
                    </div>
                  </div>
                </th>
                @if ($type != 'trashed')
                  <th>{{ __('common.status') }}</th>
                @endif
                <th class="text-end">{{ __('common.action') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in product.data" :key="item.id">
                <td><input type="checkbox" :value="item.id" v-model="selected" /></td>
                <td>@{{ item.id }}</td>
                <td>
                  <div class="wh-60 border d-flex justify-content-between align-items-center"><img :src="item.images[0] || 'image/placeholder.png'" class="img-fluid"></div>
                </td>
                <td>
                  <a :href="item.url" target="_blank" :title="item.name" class="text-dark">@{{ stringLengthInte(item.name, 90) }}</a>
                </td>
                <td>@{{ item.price_formatted }}</td>
                <td>@{{ item.created_at }}</td>
                <td>@{{ item.position }}</td>
                @if ($type != 'trashed')
                  <td>
                    <span v-if="item.active" class="text-success">{{ __('common.enable') }}</span>
                    <span v-else class="text-secondary">{{ __('common.disable') }}</span>
                  </td>
                @endif
                <td width="140" class="text-end">
                  <template v-if="item.deleted_at == ''">
                    <a :href="item.url_edit" class="btn btn-outline-secondary btn-sm">{{ __('common.edit') }}</a>
                    <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm"
                      @click.prevent="deleteProduct(index)">{{ __('common.delete') }}</a>
                  </template>
                  <template v-else>
                    <a href="javascript:void(0)" class="btn btn-outline-secondary btn-sm"
                      @click.prevent="restoreProduct(index)">{{ __('common.restore') }}</a>
                  </template>
                </td>
              </tr>
            </tbody>
          </table>

          <el-pagination layout="prev, pager, next" background :page-size="product.per_page" :current-page.sync="page"
            :total="product.total"></el-pagination>
        </template>

        <div v-else><x-admin-no-data /></div>
      </div>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#product-app',
      data: {
        product: @json($products),
        filter: {
          name: bk.getQueryString('name'),
          category_id: bk.getQueryString('category_id'),
          sku: bk.getQueryString('sku'),
          model: bk.getQueryString('model'),
          active: bk.getQueryString('active'),
        },
        selected: [],
        page: bk.getQueryString('page', 1) * 1,
        orderBy: bk.getQueryString('order_by', 'products.id:desc'),
      },

      computed: {
        url: function() {
          let filter = {};
          if (this.orderBy != 'products.id:desc') {
            filter.order_by = this.orderBy;
          }

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
          // const url = @json(admin_route('products.index'));

          @if ($type == 'products')
            const url = @json(admin_route('products.index'));
          @else
            const url = @json(admin_route('products.trashed'));
          @endif

          if (query) {
            return url + '?' + query;
          }

          return url;
        },

        allSelected: {
          get() {
            return this.selected.length == this.product.data.length;
          },
          set(val) {
            return this.selected = val ? this.product.data.map(e => e.id) : [];
          }
        }
      },

      watch: {
        page: function() {
          this.loadData();
        },

        orderBy: function() {
          this.loadData();
        }
      },
      methods: {
        loadData: function() {
          window.history.pushState('', '', this.url);
          $http.get(this.url).then((res) => {
            this.product = res;
          })
        },

        batchDelete() {
          this.$confirm('{{ __('admin/product.confirm_batch_product') }}', '{{ __('common.text_hint') }}', {
            confirmButtonText: '{{ __('common.confirm') }}',
            cancelButtonText: '{{ __('common.cancel') }}',
            type: 'warning'
          }).then(() => {
            $http.delete('products/delete', {
              ids: this.selected
            }).then((res) => {
              layer.msg(res.message)
              location.reload();
            })
          }).catch(()=>{});
        },

        batchActive(type) {
          this.$confirm('{{ __('admin/product.confirm_batch_status') }}', '{{ __('common.text_hint') }}', {
            confirmButtonText: '{{ __('common.confirm') }}',
            cancelButtonText: '{{ __('common.cancel') }}',
            type: 'warning'
          }).then(() => {
            $http.post('products/status', {
              ids: this.selected,
              status: type
            }).then((res) => {
              layer.msg(res.message)
              location.reload();
            })
          }).catch(()=>{});
        },

        search: function() {
          this.page = 1;
          this.loadData();
        },

        resetSearch() {
          Object.keys(this.filter).forEach(key => this.filter[key] = '')
          this.loadData();
        },

        deleteProduct: function(index) {
          const product = this.product.data[index];

          this.$confirm('{{ __('common.confirm_delete') }}', '{{ __('common.text_hint') }}', {
            type: 'warning'
          }).then(() => {
            $http.delete('products/' + product.id).then((res) => {
              location.reload();
            })
          });
        },

        restoreProduct: function(index) {
          const product = this.product.data[index];

          this.$confirm('{{ __('admin/product.confirm_batch_restore') }}', '{{ __('common.text_hint') }}', {
            type: 'warning'
          }).then(() => {
            $http.put('products/restore', {
              id: product.id
            }).then((res) => {
              location.reload();
            })
          });
        },

        clearRestore() {
          this.$confirm('{{ __('admin/product.confirm_delete_restore') }}', '{{ __('common.text_hint') }}', {
            type: 'warning'
          }).then(() => {
            $http.post('products/trashed/clear').then((res) => {
              location.reload();
            })
          });
        }
      }
    });
  </script>
@endpush
