@extends('admin::layouts.master')

@section('title', '货币管理')

@section('content')
    <div id="customer-app" class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between my-4">
                <a href="{{ admin_route('currencies.create') }}" class="btn btn-primary">创建</a>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>名称</th>
                  <th>编码</th>
                  <th>货币左符号</th>
                  <th>货币右符号</th>
                  <th>小数位数</th>
                  <th>汇率值</th>
                  <th>状态</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($currencies as $currency)
                    <tr>
                      <td>{{ $currency['id'] }}</td>
                      <td>{{ $currency['name'] }}</td>
                      <td>{{ $currency['code'] }}</td>
                      <td>{{ $currency['symbol_left'] }}</td>
                      <td>{{ $currency['symbol_right'] }}</td>
                      <td>{{ $currency['decimal_place'] }}</td>
                      <td>{{ $currency['value'] }}</td>
                      <td>{{ $currency['status'] }}</td>
                      <td>
                          <a class="btn btn-outline-secondary btn-sm" href="{{ admin_route('currencies.edit', [$currency['id']]) }}">编辑</a>
                      </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
        </div>
    </div>
@endsection
