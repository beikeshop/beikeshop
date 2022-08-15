@extends('admin::layouts.master')

@section('title', '分类管理')

@section('content')
  <div id="category-app" class="card">
    <div class="card-header">编辑分类</div>
    <div class="card-body">
      <form action="{{ admin_route($category->id ? 'categories.update' : 'categories.store', $category) }}"
        method="POST">
        @csrf
        @method($category->id ? 'PUT' : 'POST')
        <input type="hidden" name="_redirect" value="{{ $_redirect }}">

        @if (session('success'))
          <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
        @endif

        <x-admin-form-input-locale name="descriptions.*.name" title="名称" :value="$descriptions" required />
        <x-admin-form-input-locale name="descriptions.*.content" title="内容" :value="$descriptions" />

        {{-- <x-admin-form-select title="上级分类" name="parent_id" :value="old('parent_id', $category->parent_id ?? 0)" :options="$categories->toArray()" key="id" label="name" /> --}}

        <x-admin::form.row title="上级分类">
          @php
            $_parent_id = old('parent_id', $category->parent_id ?? 0);
          @endphp
          <select name="parent_id" id="" class="form-control short wp-400">
            <option value="0">--请选择--</option>
            @foreach ($categories as $_category)
              <option value="{{ $_category->id }}" {{ $_parent_id == $_category->id ? 'selected' : '' }}>
                {{ $_category->name }}
              </option>
            @endforeach
          </select>
        </x-admin::form.row>

        <x-admin-form-switch title="状态" name="active" :value="old('active', $category->active ?? 1)" />

        <x-admin::form.row>
          <button type="submit" class="btn btn-primary mt-3">保存</button>
        </x-admin::form.row>
      </form>

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
