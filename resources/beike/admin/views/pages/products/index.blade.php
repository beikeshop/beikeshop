@extends('admin::layouts.master')

@section('title', '商品管理')

@push('header')
  <script src="https://cdn.bootcdn.net/ajax/libs/underscore.js/1.13.3/underscore.min.js"></script>
@endpush

@section('content')

    @if ($errors->has('error'))
        <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4" />
    @endif

  <div id="product-app">
    <div class="card">
      <div class="card-body">
        <div class="bg-light p-4">
          <div class="row">
            <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
              <label class="filter-title">{{ __('product.name') }}</label>
              <input type="text" v-model="filter.name" class="form-control" placeholder="name">
            </div>
            <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
              <label class="filter-title">{{ __('product.sku') }}</label>
              <input type="text" v-model="filter.sku" class="form-control" placeholder="sku">
            </div>

            <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
              <label class="filter-title">{{ __('product.model') }}</label>
              <input type="text" v-model="filter.model" class="form-control" placeholder="model">
            </div>

            <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
              <label class="filter-title">{{ __('product.category') }}</label>
              <select v-model="filter.category_id" class="form-control">
                <option value="0">全部</option>
                @foreach ($categories as $_category)
                  <option :value="{{ $_category->id }}">{{ $_category->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
              <label class="filter-title">排序</label>
              <select v-model="filter.active" class="form-control">
                <option value="">全部</option>
                <option value="1">上架</option>
                <option value="0">下架</option>
              </select>
            </div>
          </div>

          <div class="row">
            <label class="filter-title"></label>
            <div class="col-auto">
              <button type="button" @click="search" class="btn btn-outline-primary btn-sm">筛选</button>
              <button type="button" @click="resetSearch" class="btn btn-outline-secondary btn-sm">重置</button>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between my-4">
          <a href="{{ admin_route('products.create') }}">
            <button class="btn btn-primary">创建商品</button>
          </a>

          <div class="right">
            <button class="btn btn-outline-secondary" @click="batchDelete">批量删除</button>
            <button class="btn btn-outline-secondary" @click="batchActive(true)">批量上架</button>
            <button class="btn btn-outline-secondary" @click="batchActive(false)">批量下架</button>
            {{-- <button class="btn btn-outline-secondary">批量改价</button> --}}
          </div>
        </div>

        <template v-if="product.data.length">
          <table class="table table-hover">
            <thead>
              <tr>
                <th></th>
                <th>ID</th>
                <th>图片</th>
                <th>商品名称</th>
                <th>价格</th>
                <th>
                  <div class="d-flex align-items-center">
                    创建时间
                    <div class="d-flex flex-column ml-1 order-by-wrap">
                      <i class="el-icon-caret-top" @click="orderBy = 'created_at:asc'"></i>
                      <i class="el-icon-caret-bottom" @click="orderBy = 'created_at:desc'"></i>
                    </div>
                  </div>
                </th>

                <th class="d-flex align-items-center">
                  <div class="d-flex align-items-center">
                    排序
                    <div class="d-flex flex-column ml-1 order-by-wrap">
                      <i class="el-icon-caret-top" @click="orderBy = 'position:asc'"></i>
                      <i class="el-icon-caret-bottom" @click="orderBy = 'position:desc'"></i>
                    </div>
                  </div>
                </th>
                <th>上架</th>
                <th class="text-end">操作</th>
              </tr>
            </thead>
            <tr v-for="(item, index) in product.data" :key="item.id">
              <td>
                <input type="checkbox" :value="item.id" v-model="selected" />
              </td>
              <td>@{{ item.id }}</td>
              <td>
                <div class="wh-60"><img :src="item.images[0] || 'image/placeholder.png'" class="img-fluid"></div>
              </td>
              <td>@{{ item.name || '无名称' }}</td>
              <td>@{{ item.price_formatted }}</td>
              <td>@{{ item.created_at }}</td>
              <td>@{{ item.position }}</td>
              <td>@{{ item.active ? '上架' : '下架' }}</td>
              <td width="140" class="text-end">
                <template v-if="item.deleted_at == ''">
                  <a :href="item.url_edit" class="btn btn-outline-secondary btn-sm">编辑</a>
                  <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm"
                    @click.prevent="deleteProduct(index)">删除</a>
                </template>
                <template v-else>
                  <a href="javascript:void(0)" class="btn btn-outline-secondary btn-sm"
                    @click.prevent="restoreProduct(index)">恢复</a>
                </template>
              </td>
            </tr>
          </table>

          <el-pagination layout="prev, pager, next" background :page-size="product.per_page" :current-page.sync="page"
          :total="product.total"></el-pagination>
        </template>

        <p v-else>无商品</p>
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
          this.$confirm('确认要批量删除选中的商品吗？', '删除商品', {
            type: 'warning'
          }).then(() => {
            $http.delete('products/delete', {ids: this.selected}).then((res) => {
              layer.msg(res.message)
              location.reload();
            })
          });
        },

        batchActive (type) {
          this.$confirm('确认要批量修改选中的商品的状态吗？', '修改状态', {
            type: 'warning'
          }).then(() => {
            $http.post('products/status', {ids: this.selected, status: type}).then((res) => {
              layer.msg(res.message)
              location.reload();
            })
          });
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

          this.$confirm('确认要删除商品吗？', '删除商品', {
            type: 'warning'
          }).then(() => {
            $http.delete('products/' + product.id).then((res) => {
              location.reload();
            })
          });
        },

        restoreProduct: function(index) {
          const product = this.product.data[index];

          this.$confirm('确认要恢复选中的商品吗？', '恢复商品', {
            type: 'warning'
          }).then(() => {
            $http.put('products/restore', {id: product.id}).then((res) => {
              location.reload();
            })
          });
        }
      }
    });
  </script>
@endpush
