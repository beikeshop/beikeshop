@extends('layout.master')

@section('content')
    <div class="container">
        <h1>我的地址</h1>
        @foreach ($addresses as $address)
            <div class="col-6 col-md-3">{{ $address->name }}</div>
        @endforeach

    </div>
@endsection
