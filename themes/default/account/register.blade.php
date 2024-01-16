@extends('layout.master')

@section('content')
  <div class="container">
    <h1>Register</h1>
    <form action="{{ route('shop.register.store') }}" method="post">
      @csrf

      @hookwrapper('account.register.email')
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="email">邮箱</span>
          </div>
          <input type="text" name="register[email]" class="form-control" value="{{ old('register.email') }}"
                 placeholder="邮箱地址">
        </div>
        @error('register.email')
        <x-admin::form.error :message="$message"/>
        @enderror
      </div>
      @endhookwrapper

      @hookwrapper('account.register.password')
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="password">密码</span>
          </div>
          <input type="password" name="register[password]" class="form-control" placeholder="密码">
        </div>
        @error('register.password')
        <x-admin::form.error :message="$message"/>
        @enderror
      </div>
      @endhookwrapper

      @hook('account.register.email.after')

      @if (session('error'))
        <div class="alert alert-success">
          {{ session('error') }}
        </div>
      @endif

      <button type="submit" class="btn btn-primary btn-block mb-4">登录</button>
    </form>
  </div>
@endsection
