@extends('admin::layouts.master')

@section('title', '顾客管理')

@section('content')
    <div id="customer-app" class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between my-4">
                <a href="{{ admin_route('customers.create') }}" class="btn btn-primary">创建顾客</a>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>邮箱</th>
                  <th>名称</th>
                  <th>注册来源</th>
                  <th>状态</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($customers as $customer)
                    <tr v-for="customer, index in customers" :key="index">
                      <td>{{ $customer['id'] }}</td>
                      <td>{{ $customer['email'] }}</td>
                      <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $customer['avatar'] }}" class="img-fluid rounded-circle me-2" style="width: 40px;">
                            <div>{{ $customer['name'] }}</div>
                        </div>
                      </td>
                      <td>{{ $customer['from'] }}</td>
                      <td>{{ $customer['status'] }}</td>
                      <td>
                          <a class="btn btn-outline-secondary btn-sm" href="{{ admin_route('customers.edit', [$customer['id']]) }}">编辑</a>
                      </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
        </div>
    </div>
@endsection