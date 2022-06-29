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
                    <th width="60%">插件描述</th>
                    <th>状态</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>Mark</td>
                    <td>
                        <div class="plugin-describe d-flex align-items-center">
                            <div class="me-2" style="width: 50px;"><img src="http://dummyimage.com/100x100" class="img-fluid"></div>
                            <div>
                                <h6>插件名称</h6>
                                <div class="">插件详细描述，插件详细描述，插件详细描述插件详细描述插件详细描述插件详细描述</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" role="switch" id="switch-1" checked>
                          <label class="form-check-label" for="switch-1"></label>
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-outline-secondary btn-sm">编辑</button>
                    </td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>Mark</td>
                    <td>Mark</td>
                    <td>
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" role="switch" id="switch-2">
                          <label class="form-check-label" for="switch-2" checked></label>
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-outline-secondary btn-sm">编辑</button>
                    </td>
                  </tr>
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
