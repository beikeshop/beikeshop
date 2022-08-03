@extends('admin::layouts.master')

@section('title', '售后申请')

@section('content')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">售后申请详情</h6></div>
    <div class="card-body">
      <div class="row">
        <div class="col-4">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>ID：</td>
                <td>{{ $rma->id }}</td>
              </tr>
              <tr>
                  <td>客户姓名：</td>
                  <td>{{ $rma->name }}</td>
              </tr>
              <tr>
                  <td>联系电话：</td>
                  <td>{{ $rma->telephone }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-4">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>商品：</td>
                <td>{{ $rma->product_name }}</td>
              </tr>
              <tr>
                <td>型号：</td>
                <td>{{ $rma->model }}</td>
              </tr>
              <tr>
                <td>数量：</td>
                <td>{{ $rma->quantity }}</td>
              </tr>
              <tr>
                <td>退货原因：</td>
                <td>{{ $rma->quantity }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">操作日志</h6></div>
    <div class="card-body">

    </div>
  </div>
@endsection


