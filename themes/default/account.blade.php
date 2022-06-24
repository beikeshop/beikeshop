@extends('layout.master')

@section('content')
    <div class="container">
        <h1>Account</h1>
        尊敬的会员 {{ $email }}， 欢迎您回来。
        <br/>
        <br/>
        <a href="{{ route('shop.logout') }}">退出</a>
    </div>

@endsection
