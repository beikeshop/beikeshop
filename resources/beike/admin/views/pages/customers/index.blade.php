@extends('admin::layouts.master')

@section('title', '顾客管理')

@push('header')
    <style>
        .el-tree-node__content {
            height: 32px;
            border-bottom: 1px solid #f9f9f9;
        }
    </style>
@endpush

@section('content')
    <div id="customer-app" class="card">
        <div class="card-body">
            <a href="{{ admin_route('customers.create') }}" class="btn btn-primary">创建顾客</a>
            <div class="mt-4" style="">
                <el-tree :data="categories" default-expand-all :expand-on-click-node="false">
                    <div class="custom-tree-node" slot-scope="{ node, data }" style="flex:1;display:flex">
                        <span>@{{ data.id }}</span>
                        <span>@{{ data.email }}</span>
                        <span>@{{ data.avatar }}</span>
                        <span>@{{ data.from }}</span>
                        <div style="flex:1"></div>
                        <span class="mr-4">@{{ data.status ? '启用' : '禁用' }}</span>
                        <div>
                            <a :href="data.url_edit">编辑</a>
                            <a>删除</a>
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
      el: '#customer-app',
      data: {
        categories: @json($customers),
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
