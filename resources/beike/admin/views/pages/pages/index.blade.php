@extends('admin::layouts.master')

@section('title', '信息页面')

@section('content')
  <div class="card">
    <div class="card-body h-min-600">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary">添加</button>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>税种</th>
            <th>税率</th>
            <th>类型</th>
            <th>区域</th>
            <th>创建时间</th>
            <th>修改时间</th>
            <th class="text-end">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td class="text-end">1</td>
          </tr>
        </tbody>
      </table>

      {{-- {{ $tax_rates->links('admin::vendor/pagination/bootstrap-4') }} --}}
    </div>
  </div>
@endsection
