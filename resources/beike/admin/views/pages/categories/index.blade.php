@extends('admin::layouts.master')

@section('title', '分类管理')

@section('body-class', 'page-categories')

@section('content')
  <div id="category-app" class="card">
    <div class="card-body">
      <a href="{{ admin_route('categories.create') }}" class="btn btn-primary">创建分类</a>
      <div class="mt-4" style="">
        <el-tree :data="categories" node-key="id" ref="tree">
          <div class="custom-tree-node d-flex align-items-center justify-content-between w-100" slot-scope="{ node, data }">
            <div><span>@{{ data.name }}</span></div>
            <div class="d-flex align-items-center">
              <span :class="['me-4', 'badge', 'bg-' + (data.active ? 'success' : 'secondary')]">@{{ data.active ? '启用' : '禁用' }}</span>
              <div>
                <a :href="data.url_edit" class="btn btn-outline-info btn-sm">编辑</a>
                <a class="btn btn-outline-danger btn-sm" @click="removeCategory(node, data)">删除</a>
              </div>
            </div>
          </div>
        </el-tree>
      </div>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#category-app',
      data: {
        categories: @json($categories),
        defaultProps: {
          children: 'children',
          label: 'name'
        }
      },

      methods: {
        removeCategory(node, data) {
          $http.delete(`/categories/${data.id}`).then((res) => {
            layer.msg(res.message);
            this.$refs.tree.remove(data.id)
          })
        },
      }
    });
  </script>
@endpush
