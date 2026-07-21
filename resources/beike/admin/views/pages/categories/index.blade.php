@extends('admin::layouts.master')

@section('title', __('admin/common.category'))

@section('body-class', 'page-categories')

@section('content')
  @if ($errors->has('error'))
  <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4"/>
  @endif

  @if (session()->has('success'))
  <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
  @endif

  <div id="category-app" class="card h-min-600" v-cloak>
    <div class="card-body">
      @hook('admin.categories.index.content.before')

      <div class="d-flex">
        @hook('admin.categories.index.top_buttons.before')
        <a href="{{ admin_route('categories.create') }}" class="btn btn-primary">{{ __('admin/category.categories_create') }}</a>
        @hook('admin.categories.create.after')
        <div class="ms-4 wp-300">
          <el-input
            v-model="filterKeyword"
            clearable
            style="max-width: 360px;"
            placeholder="{{ __('common.input') }}"
            size="small"
            @input="handleFilterInput"
            @clear="clearFilter">
          </el-input>
        </div>
      </div>


      @hook('admin.categories.list.before')
      <div class="mt-4 categories-wrap" style="" v-if="categories.length">
        <el-tree
          :data="categories"
          :expand-on-click-node="false"
          :filter-node-method="filterNode"
          node-key="id"
          ref="tree"
          @node-click="(node, data) => handleNodeClick(node, data)">
          <div class="custom-tree-node d-flex align-items-center justify-content-between w-100" slot-scope="{ node, data }">
            <div><span>@{{ data.id }} - @{{ data.name }}</span></div>
            <div class="d-flex align-items-center">
              <span class="me-4">{{ __('common.sort_order') }} - @{{ data.position }}</span>
              <span class="me-4 badge bg-success" v-if="data.active">{{ __('common.enabled') }}</span>
              <span class="me-4 badge bg-secondary" v-else>{{ __('common.disabled') }}</span>
              <div>
                {{-- <button class="btn btn-outline-secondary btn-sm" @click.stop="toEdit(data.url_edit)">{{ __('common.edit') }}</button> --}}
                <button class="btn btn-outline-danger btn-sm" @click.stop="removeCategory(node, data)">{{ __('common.delete') }}</button>
                @hook('admin.categories.delete.after')
              </div>
            </div>
          </div>
        </el-tree>
      </div>
      <div v-else><x-admin-no-data /></div>

      @hook('admin.categories.index.content.after')
    </div>
  </div>
@endsection

@push('footer')
  <script>
    @hook('admin.categories.index.script.before')

    let category_app = new Vue({
      el: '#category-app',
      data: {
        categories: @json($categories),
        filterKeyword: '',
        filterTimer: null,
        visibleNodeMap: {},
        expandedParentIds: [],
        defaultProps: {
          children: 'children',
          label: 'name'
        },
        @hook('admin.categories.index.vue.data')
      },

      methods: {
        handleNodeClick(node, data) {
          window.location.href = data.data.url_edit
        },

        handleFilterInput() {
          clearTimeout(this.filterTimer);
          this.filterTimer = setTimeout(() => {
            this.applyTreeFilter();
          }, 300);
        },

        clearFilter() {
          this.filterKeyword = '';
          this.applyTreeFilter();
        },

        applyTreeFilter() {
          if (!this.$refs.tree) return;

          const keyword = (this.filterKeyword || '').toString().trim().toLowerCase();
          if (!keyword) {
            this.visibleNodeMap = {};
            this.expandedParentIds = [];
            this.$refs.tree.filter('');

            return;
          }

          const visibleNodeMap = {};
          const expandedParentMap = {};

          const walk = (nodes, parentIds = []) => {
            (nodes || []).forEach((item) => {
              const idValue = item.id === undefined || item.id === null ? '' : item.id;
              const nameValue = item.name === undefined || item.name === null ? '' : item.name;
              const idStr = idValue.toString().toLowerCase();
              const nameStr = nameValue.toString().toLowerCase();
              const matched = idStr.includes(keyword) || nameStr.includes(keyword);

              if (matched) {
                visibleNodeMap[item.id] = true;
                parentIds.forEach((parentId) => {
                  visibleNodeMap[parentId] = true;
                  expandedParentMap[parentId] = true;
                });
              }

              walk(item.children || [], parentIds.concat(item.id));
            });
          };

          walk(this.categories);
          this.visibleNodeMap = visibleNodeMap;
          this.expandedParentIds = Object.keys(expandedParentMap).map((id) => {
            const parsedId = Number(id);
            return Number.isNaN(parsedId) ? id : parsedId;
          });
          this.$refs.tree.filter(keyword);

          this.$nextTick(() => {
            this.expandedParentIds.forEach((id) => {
              const treeNode = this.$refs.tree.getNode(id);
              if (treeNode) {
                treeNode.expanded = true;
              }
            });
          });
        },

        filterNode(value, data) {
          if (!value) return true;

          return !!this.visibleNodeMap[data.id];
        },

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

        toEdit(url) {
          window.location.href = url
        },

        @hook('admin.categories.index.vue.methods')
      },

      @hook('admin.categories.index.vue.options')
    });

    @hook('admin.categories.index.script.after')
  </script>
@endpush
