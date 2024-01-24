@extends('admin::layouts.master')

@section('title', __('admin/common.category'))

@section('body-class', 'page-categories')

@section('content')
  <div id="category-app" class="card h-min-600">
    <div class="card-body">
      <a href="{{ admin_route('categories.create') }}" class="btn btn-primary">{{ __('admin/category.categories_create') }}</a>
      @hook('admin.categories.create.after')
      <div class="mt-4" style="" v-if="categories.length">
        <el-tree :data="categories" node-key="id" ref="tree">
          <div class="custom-tree-node d-flex align-items-center justify-content-between w-100" slot-scope="{ node, data }">
            <div><span>@{{ data.id }} - @{{ data.name }}</span></div>
            <div class="d-flex align-items-center">
              <span class="me-4 badge bg-success" v-if="data.active">{{ __('common.enabled') }}</span>
              <span class="me-4 badge bg-secondary" v-else>{{ __('common.disabled') }}</span>
              <div>
                <a :href="data.url_edit" class="btn btn-outline-secondary btn-sm">{{ __('common.edit') }}</a>
                <a class="btn btn-outline-danger btn-sm" @click="removeCategory(node, data)">{{ __('common.delete') }}</a>
              </div>
            </div>
          </div>
        </el-tree>
      </div>
      <div v-else><x-admin-no-data /></div>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    let category_app = new Vue({
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
          this.$confirm('{{ __('common.confirm_delete') }}', '{{ __('common.text_hint') }}', {
            confirmButtonText: '{{ __('common.confirm') }}',
            cancelButtonText: '{{ __('common.cancel') }}',
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
