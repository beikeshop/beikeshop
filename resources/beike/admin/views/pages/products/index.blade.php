@extends('admin::layouts.master')

@section('title', '商品管理')

@push('header')
  <script src="https://cdn.bootcdn.net/ajax/libs/axios/0.27.2/axios.min.js"></script>
  <script src="https://cdn.bootcdn.net/ajax/libs/underscore.js/1.13.3/underscore.min.js"></script>
@endpush

@section('content')
  <div id="product-app">
    <div class="card">
      <div class="card-body">


        <div class="bg-light p-4">
          <div class="row">
            <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
              <label class="filter-title">商品名称</label>
              <input type="text" v-model="filter.keyword" class="form-control" placeholder="keyword">
            </div>
            <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
              <label class="filter-title">sku</label>
              <input type="text" v-model="filter.sku" class="form-control" placeholder="sku">
            </div>

            <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
              <label class="filter-title">分类</label>
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
              <button type="button" @click="search" class="btn btn-primary">筛选</button>
              <button type="button" @click="search" class="btn btn-outline-primary">重置</button>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between my-4">
          <a href="{{ route('admin.products.create') }}">
            <button class="btn btn-primary">创建商品</button>
          </a>

          <div class="right">
            <button class="btn btn-outline-secondary">批量删除</button>
            <button class="btn btn-outline-secondary">批量上架</button>
            <button class="btn btn-outline-secondary">批量下架</button>
            <button class="btn btn-outline-secondary">批量改价</button>
          </div>
        </div>

        <template v-if="items.length">
          <table class="table" v-loading="loading">
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
                <th>操作</th>
              </tr>
            </thead>
            <tr v-for="(item, index) in items" :key="item.id">
              <td>
                <input type="checkbox" :value="item.id" v-model="selected" />
              </td>
              <td>@{{ item.id }}</td>
              <td><img :src="item.image" style="max-width: 100px;" alt="" srcset=""></td>
              <td>@{{ item.name || '无名称' }}</td>
              <td>@{{ item.price_formatted }}</td>
              <td>@{{ item.created_at }}</td>
              <td>@{{ item.position }}</td>
              <td>@{{ item.active ? '上架' : '下架' }}</td>
              <td>
                <a :href="item.url_edit">编辑</a>
                <template>
                  <a v-if="item.deleted_at == ''" href="javascript:void(0)" @click.prevent="deleteProduct(index)">删除</a>
                  <a v-else href="javascript:void(0)" @click.prevent="restoreProduct(index)">恢复</a>
                </template>
              </td>
            </tr>
          </table>

          <el-pagination layout="prev, pager, next" background :page-size="perPage" :current-page.sync="page"
            :total="totals"></el-pagination>
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
        filter: {
          keyword: @json(request('keyword') ?? ''),
          category_id: @json(request('category_id') ?? ''),
          sku: @json(request('sku') ?? ''),
          active: @json(request('active') ?? ''),
        },
        items: [],
        selected: [],
        page: @json((int) request('page') ?? 1),
        totals: 0,
        perPage: @json((int) (request('per_page') ?? 10)),
        loading: false,
        orderBy: @json(request('order_by', 'products.id:desc')),
      },
      mounted: function() {
        this.load();
      },
      computed: {
        url: function() {
          let filter = {};
          filter.per_page = this.perPage;
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
          const url = @json(admin_route('products.index'));
          if (query) {
            return url + '?' + query;
          }
          return url;
        }
      },
      watch: {
        page: function() {
          this.load();
        },
        orderBy: function() {
          this.load();
        }
      },
      methods: {
        load: function() {
          const url = this.url;
          window.history.pushState('', '', url);
          this.loading = true;
          axios.get(url).then(response => {
            this.loading = false;
            this.items = response.data.data;
            this.totals = response.data.meta.total;
          }).catch(error => {
            // this.$message.error(error.response.data.message);
          });
        },

        search: function() {
          this.page = 1;
          this.load();
        },

        deleteProduct: function(index) {
          const product = this.items[index];

          this.$confirm('确认要删除选中的商品吗？', '删除商品', {
            // confirmButtonText: '确定',
            // cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            axios.delete('products/' + product.id).then(response => {
              location.reload();
            }).catch(error => {
              // this.$message.error(error.response.data.message);
            });
          });
        },

        restoreProduct: function(index) {
          const product = this.items[index];

          this.$confirm('确认要恢复选中的商品吗？', '恢复商品', {
            type: 'warning'
          }).then(() => {
            axios.put('products/restore', {
              id: product.id
            }).then(response => {
              location.reload();
            }).catch(error => {
              // this.$message.error(error.response.data.message);
            });
          });
        }
      }
    });
  </script>
@endpush
