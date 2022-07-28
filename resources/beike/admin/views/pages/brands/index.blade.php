@extends('admin::layouts.master')

@section('title', '品牌管理')

@section('content')
  <div id="brand-app" class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between my-4">
        <a href="{{ admin_route('currencies.create') }}" class="btn btn-primary">创建</a>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>名称</th>
            <th>图标</th>
            <th>排序</th>
            <th>状态</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($brands as $brand)
            <tr>
              <td>{{ $brand['id'] }}</td>
              <td>{{ $brand['name'] }}</td>
              <td>{{ $brand['logo'] }}</td>
              <td>{{ $brand['sort_order'] }}</td>
              <td>{{ $brand['status'] }}</td>
              <td>
                <a class="btn btn-outline-secondary btn-sm"
                  href="{{ admin_route('brands.edit', [$brand['id']]) }}">编辑</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
