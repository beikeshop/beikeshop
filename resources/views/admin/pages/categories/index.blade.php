@extends('admin.layouts.master')

@section('title', '分类管理')

@section('content')
  <div id="category-app" class="card">
    <div class="card-header">
      所有分类
    </div>
    <div class="card-body">
      <a href="{{ admin_route('categories.create') }}" class="btn btn-primary">创建分类</a>
      <div class="mt-4" style="max-width: 800px;">
        <el-tree :data="categories" default-expand-all :expand-on-click-node="false">
          <div class="custom-tree-node" slot-scope="{ node, data }" style="flex:1;display:flex">
            <span>@{{ data.name }}</span>
            <div style="flex:1"></div>
            <span class="mr-4">@{{ data.active ? '启用' : '禁用' }}</span>
            <div>
              <a :href="data.url_edit">Edit</a>
              <a>Delete</a>
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
        handleNodeClick(data) {
          console.log(data);
        }
      }
    });
</script>
@endpush
