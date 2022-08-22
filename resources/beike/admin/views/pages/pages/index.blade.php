@extends('admin::layouts.master')

@section('title', '信息页面')

@section('content')

  @if ($errors->has('error'))
    <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4" />
  @endif

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
              <td>
                <div title="{{ $page['title'] ?? '' }}">{{ $page['title_format'] ?? '' }}</div>
              </td>
              <td class="{{ $page['active'] ? 'text-success' : 'text-secondary'}}">
                {{ $page['active'] ? __('common.enable') : __('common.disable') }}
              </td>
              <td>{{ $page['created_at'] }}</td>
              <td>{{ $page['updated_at'] }}</td>
              <td class="text-end">
                <a href="{{ admin_route('pages.edit', [$page['id']]) }}" class="btn btn-outline-secondary btn-sm">编辑</a>
                {{-- <form action="{{ admin_route('pages.destroy', [$page['id']]) }}" method="post" class="d-inline-block">
                {{ method_field('delete') }}
                {{ csrf_field() }}
              </form> --}}
                <button class="btn btn-outline-danger btn-sm delete-btn" type='button'
                  data-id="{{ $page['id'] }}">删除</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      {{ $pages->links('admin::vendor/pagination/bootstrap-4') }}

    </div>
  </div>
@endsection

@push('footer')
  <script>
    $('.delete-btn').click(function(event) {
      const id = $(this).data('id');
      const self = $(this);

      layer.confirm('确定要删除页面吗？', {
        title: "提示",
        btn: ['取消', '确定'],
        area: ['400px'],
        btn2: () => {
          $http.delete(`pages/${id}`).then((res) => {
            layer.msg(res.message);
            window.location.reload();
          })
        }
      })
    });
  </script>
@endpush
