@extends('admin::layouts.master')

@section('title', '后台用户')

@section('content')
  <ul class="nav-bordered nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link" href="{{ admin_route('admin_users.index') }}">后台用户</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="{{ admin_route('admin_roles.index') }}">用户角色</a>
    </li>
  </ul>

  <div id="tax-classes-app" class="card">
    <div class="card-body h-min-600">
      <div class="d-flex justify-content-between mb-4">
        <a href="{{ admin_route('admin_roles.create') }}" class="btn btn-primary">创建角色</a>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>名称</th>
            <th>创建时间</th>
            <th>修改时间</th>
            <th class="text-end">操作</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($roles as $role)
          <tr>
            <td>{{ $role->id }}</td>
            <td>{{ $role->name }}</td>
            <td>{{ $role->created_at }}</td>
            <td>{{ $role->updated_at }}</td>
            <td class="text-end">
              <a href="{{ admin_route('admin_roles.edit', [$role->id]) }}" class="btn btn-outline-secondary btn-sm">编辑</a>
              <button class="btn btn-outline-danger btn-sm ml-1 delete-role" data-id="{{ $role->id }}" type="button">删除</button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    $('.delete-role').click(function(event) {
      const id = $(this).data('id');
      const self = $(this);

      layer.confirm('确定要删除角色吗？', {
        title: "提示",
        btn: ['取消', '确定'],
        btn2: () => {
        $http.delete(`admin_roles/${id}`).then((res) => {
            layer.msg(res.message);
            self.parents('tr').remove()
          })
        }
      })
    });
  </script>
@endpush