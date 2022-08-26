@extends('admin::layouts.master')

@section('title', __('admin/common.category'))

@section('body-class', 'page-categories')

@section('content')
  <div id="category-app" class="card">
    <div class="card-body">
      <a href="{{ admin_route('categories.create') }}" class="btn btn-primary">{{ __('admin/category.categories_create') }}</a>
      <div class="mt-4" style="">
        <el-tree :data="categories" node-key="id" ref="tree">
          <div class="custom-tree-node d-flex align-items-center justify-content-between w-100" slot-scope="{ node, data }">
            <div><span>@{{ data.name }}</span></div>
            <div class="d-flex align-items-center">
              <span :class="['me-4', 'badge', 'bg-' + (data.active ? 'success' : 'secondary')]">@{{ data.active ? '启用' : '禁用' }}</span>
              <div>
                <a :href="data.url_edit" class="btn btn-outline-secondary btn-sm">{{ __('common.edit') }}</a>
                <a class="btn btn-outline-danger btn-sm" @click="removeCategory(node, data)">{{ __('common.delete') }}</a>
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
          this.$confirm('确定要删除分类吗？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            $http.delete(`/categories/${data.id}`).then((res) => {
              layer.msg(res.message);
              this.$refs.tree.remove(data.id)
            })
          }).catch(()=>{})
        },
      }
    });
  </script>
@endpush
