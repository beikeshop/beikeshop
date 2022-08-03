@extends('admin::layouts.master')

@section('title', '售后申请列表')

@section('content')
    <div id="customer-app" class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th>客户姓名</th>
                    <th>邮箱</th>
                    <th>电话号码</th>
                    <th>商品</th>
                    <th>商品型号</th>
                    <th>数量</th>
                    <th>服务类型</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($rmas as $rma)
                    <tr>
                        <td>{{ $rma->name }}</td>
                        <td>{{ $rma->email }}</td>
                        <td>{{ $rma->telephone }}</td>
                        <td>{{ $rma->product_name }}</td>
                        <td>{{ $rma->model }}</td>
                        <td>{{ $rma->quantity }}</td>
                        <td>{{ $rma->type }}</td>
                        <td>{{ $rma->status }}</td>
                        <td><a href="{{ admin_route('rmas.show', [$rma->id]) }}" class="btn btn-outline-secondary btn-sm">查看</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $rmas->links('admin::vendor/pagination/bootstrap-4') }}
        </div>
    </div>
@endsection

@push('footer')
    <script>

    </script>
@endpush
