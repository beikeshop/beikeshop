@extends('admin.layouts.master')

@section('title', '商品管理')

@section('content')
  <div class="card">
    <div class="card-body">
      <x-filter :url="route('admin.products.index')" />
    </div>
  </div>

  <div class="card mt-4" id="product-app">
    <div class="card-header">
      <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Create</a>
    </div>
    <div class="card-body">
      <table v-if="items.length" class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>图片</th>
            <th>商品名称</th>
            <th>价格</th>
            <th>创建时间</th>
            <th>上架</th>
            <th>操作</th>
          </tr>
        </thead>
        <tr v-for="item in items" :key="item.id">
          <td>@{{ item.id }}</td>
          <td><img :src="item.image" alt="" srcset=""></td>
          <td>@{{ item.name }}</td>
          <td>@{{ item.price_formatted }}</td>
          <td>@{{ item.created_at }}</td>
          <td>@{{ item.active ? '上架' : '下架' }}</td>
          <td>
            <a :href="item.url_edit">编辑</a>
            <a href="#" @click.prevent="deleteProducts">删除</a>
          </td>
        </tr>
      </table>

      {{ $products->links() }}

      <p v-if="items.length < 1">无商品</p>
    </div>
  </div>

@endsection

@push('footer')
  <script>
    new Vue({
      el: '#product-app',
      data: {
        items: @json($products->items()),
      },
      methods: {
        deleteProducts: function () {
          this.$confirm('确认要删除选中的商品吗？', '删除商品', {
            // confirmButtonText: '确定',
            // cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            //
          });
        }
      }
    });
  </script>
@endpush
