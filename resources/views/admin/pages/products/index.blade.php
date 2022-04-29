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
        <tr v-for="item in items" :key="item.id">
          <td>@{{ item.id }}</td>
          <td><img :src="item.image" alt="" srcset=""></td>
          <td>@{{ item.name }}</td>
          <td>@{{ item.variables ? '多规格' : '单规格' }}</td>
          <td>
            <a :href="item.url_edit">编辑</a>
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
      }
    });
  </script>
@endpush

