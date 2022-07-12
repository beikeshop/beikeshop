@extends('admin::layouts.master')

@section('title', '后台管理')

@section('content')
  @for ($i = 0; $i < 5; $i++)
    <div class="card mb-3">
      <div class="card-header">订单统计</div>
      <div class="card-body">
        <div>11</div>
      </div>
    </div>
  @endfor
@endsection
