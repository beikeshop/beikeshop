@extends('admin::layouts.master')

@section('title', '品牌管理')

@section('content')
  <div id="manufacturer-app" class="card">
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
          @foreach ($manufacturers as $manufacturer)
            <tr>
              <td>{{ $manufacturer['id'] }}</td>
              <td>{{ $manufacturer['name'] }}</td>
              <td>{{ $manufacturer['logo'] }}</td>
              <td>{{ $manufacturer['sort_order'] }}</td>
              <td>{{ $manufacturer['status'] }}</td>
              <td>
                <a class="btn btn-outline-secondary btn-sm"
                  href="{{ admin_route('manufacturers.edit', [$manufacturer['id']]) }}">编辑</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
