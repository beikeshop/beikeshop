@extends('admin::layouts.master')

@section('title', '售后申请列表')

@section('content')
    <div id="customer-app" class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-4">
                <button type="button" class="btn btn-primary" @click="checkedCreate('add', null)">创建</button>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($rmaReasons as $rmaReason)
                    <tr>
                        <td>{{ $rmaReason->id }}</td>
                        <td>{{ $rmaReason->name }}</td>
                        <td><a href="{{ admin_route('rma_reasons.show', [$rmaReason->id]) }}" class="btn btn-outline-secondary btn-sm">查看</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $rmaReasons->links('admin::vendor/pagination/bootstrap-4') }}
        </div>
    </div>
@endsection

@push('footer')
    <script>

    </script>
@endpush
