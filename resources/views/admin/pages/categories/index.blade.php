@extends('admin.layouts.master')

@section('title', '分类管理')

@section('content')
  <div id="category-app" class="card">
    <div class="card-header">
      所有分类
    </div>
    <div class="card-body">
      <el-tree :data="data" :props="defaultProps" :default-expand-all="true" @node-click="handleNodeClick"></el-tree>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#category-app',
      data() {
        return {
          data: [{
            label: '一级 1',
            children: [{
              label: '二级 1-1',
              children: [{
                label: '三级 1-1-1'
              }]
            }]
          }, {
            label: '一级 2',
            children: [{
              label: '二级 2-1',
              children: [{
                label: '三级 2-1-1'
              }]
            }, {
              label: '二级 2-2',
              children: [{
                label: '三级 2-2-1'
              }]
            }]
          }, {
            label: '一级 3',
            children: [{
              label: '二级 3-1',
              children: [{
                label: '三级 3-1-1'
              }]
            }, {
              label: '二级 3-2',
              children: [{
                label: '三级 3-2-1'
              }]
            }]
          }],
          defaultProps: {
            children: 'children',
            label: 'label'
          }
        };
      },

      methods: {
        handleNodeClick(data) {
          console.log(data);
        }
      }
    });
</script>
@endpush
