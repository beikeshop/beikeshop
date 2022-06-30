@extends('admin::layouts.master')

@section('title', '插件列表')

@section('content')
  <div id="category-app" class="card">
    <div class="card-body">
      <a href="{{ admin_route('categories.create') }}" class="btn btn-primary">创建插件</a>
      <div class="mt-4" style="">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>插件类型</th>
              <th width="55%">插件描述</th>
              <th>状态</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($plugins as $plugin)
              <tr>
                <td>{{ $plugin->code }}</td>
                <td>{{ $plugin->type }}</td>
                <td>
                  <div class="plugin-describe d-flex align-items-center">
                    <div class="me-2" style="width: 50px;"><img src="{{ $plugin->icon }}" class="img-fluid"></div>
                    <div>
                      <h6>{{ $plugin->name }}</h6>
                      <div class="">{!! $plugin->description !!}</div>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="switch-1"
                      {{ $plugin->enabled ? 'checked' : '' }}>
                    <label class="form-check-label" for="switch-1"></label>
                  </div>
                </td>
                <td>
                  <a class="btn btn-outline-secondary btn-sm" href="{{ $plugin->getEditUrl() }}">编辑</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    $('.form-switch input[type="checkbox"]').change(function(event) {
      console.log($(this).prop('checked'))
    });
  </script>
@endpush
