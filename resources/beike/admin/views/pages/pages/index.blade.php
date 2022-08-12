@extends('admin::layouts.master')

@section('title', '信息页面')

@section('content')
  <div class="card">
    <div class="card-body h-min-600">
      <div class="d-flex justify-content-between mb-4">
        <a href="{{ admin_route('pages.create') }}" class="btn btn-primary">添加</a>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>标题</th>
            <th>状态</th>
            <th>创建时间</th>
            <th>修改时间</th>
            <th class="text-end">操作</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pages_format as $page)
          <tr>
            <td>{{ $page['id'] }}</td>
            <td>{{ $page['title_format'] ?? '' }}</td>
            <td>{{ $page['active'] }}</td>
            <td>{{ $page['created_at'] }}</td>
            <td>{{ $page['updated_at'] }}</td>
            <td class="text-end">
              <a href="{{ admin_route('pages.edit', [$page['id']]) }}" class="btn btn-outline-secondary btn-sm">编辑</a>
              <form action="{{ admin_route('pages.destroy', [$page['id']]) }}" method="post" class="d-inline-block">
                {{ method_field('delete') }}
                {{ csrf_field() }}
                <button class="btn btn-outline-danger btn-sm">删除</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      {{ $pages->links('admin::vendor/pagination/bootstrap-4') }}

    </div>
  </div>
@endsection
