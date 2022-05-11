@extends('beike::admin.layouts.master')

@section('title', '商品管理')

@push('header')
  <script src="https://cdn.bootcdn.net/ajax/libs/axios/0.27.2/axios.min.js"></script>
@endpush

@section('content')
  <div id="product-app">
    <div class="card">
      <div class="card-body">
        <div class="form-inline">
          <input type="text" v-model="filter.keyword" class="form-control mr-2">
          <select v-model="filter.category_id" class="form-control">
            <option value="">--全部--</option>
            @foreach ($categories as $_category)
              <option :value="{{ $_category->id }}">{{ $_category->name }}</option>
            @endforeach
          </select>

          <button type="button" @click="search" class="btn btn-primary">筛选</button>
        </div>
      </div>
    </div>

    <div class="card mt-4">
      <div class="card-header">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Create</a>
      </div>
      <div class="card-body">
        <table v-if="items.length" class="table">
          <thead>
            <tr>
              <th></th>
              <th>ID</th>
              <th>图片</th>
              <th>商品名称</th>
              <th>价格</th>
              <th>创建时间</th>
              <th>上架</th>
              <th>操作</th>
            </tr>
          </thead>
          <tr v-for="(item, index) in items" :key="item.id">
            <td>
              <input type="checkbox" :value="item.id" v-model="selected" />
            </td>
            <td>@{{ item.id }}</td>
            <td><img :src="item.image" alt="" srcset=""></td>
            <td>@{{ item.name || '无名称' }}</td>
            <td>@{{ item.price_formatted }}</td>
            <td>@{{ item.created_at }}</td>
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

        <p v-if="items.length < 1">无商品</p>
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
          keyword: '',
          category_id: 0,
        },
        items: [],
        selected: [],
        page: 1,
      },
      mounted: function () {
        this.load();
      },
      methods: {
        load: function () {
          const url = `products?keyword=${this.filter.keyword}&category_id=${this.filter.category_id}&page=${this.page}`;
          axios.get(url).then(response => {
            this.items = response.data.data;
          }).catch(error => {
            // this.$message.error(error.response.data.message);
          });
        },

        search: function () {
          this.load();
        },

        deleteProduct: function (index) {
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

        restoreProduct: function (index) {
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
