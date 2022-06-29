@extends('layout.master')

@section('body-class', 'page-login')

@section('content')
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>
    <div class="hero-content pb-5 text-center"><h1 class="hero-heading">用户登录与注册</h1></div>
    <div class="justify-content-center row mb-5">
      <div class="col-lg-5">
        <div class="card">
          <div class="login-item-header card-header">
            <h6 class="text-uppercase mb-0">Login</h6>
          </div>
          <div class="card-body">
            <p class="lead">Already our customer?</p>
            <p class="text-muted">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis
              egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit
              amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
            <hr>

            <form action="{{ route('shop.login.store') }}" method="post">
              @csrf

              <div class="mb-4">
                <label class="form-label" for="email_1">Email</label>
                <input type="text" name="login[email]" class="form-control" value="{{ old('login.email') }}" placeholder="邮箱地址">
                @error('login.email')
                  <x-admin::form.error :message="$message" />
                @enderror
              </div>

              <div class="mb-4">
                <label class="form-label" for="email_1">Password</label>
                <input type="password" name="login[password]" class="form-control" placeholder="密码">
                @error('login.password')
                  <x-admin::form.error :message="$message" />
                @enderror
              </div>

              @if (session('error'))
                <div class="alert alert-success">
                  {{ session('error') }}
                </div>
              @endif

              <div class="mb-4">
                <button type="submit" class="btn btn-outline-dark"><i class="bi bi-box-arrow-in-right"></i> 登录</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="card">
          <div class="login-item-header card-header">
            <h6 class="text-uppercase mb-0">New account</h6>
          </div>
          <div class="card-body">
            <p class="lead">Not our registered customer yet?</p>
            <p class="text-muted">With registration with us new world of fashion, fantastic discounts and much more opens to
              you! The whole process will not take you more than a minute!</p>
            <p class="text-muted">If you have any questions, please feel free to <a href="/contact">contact us</a>, our
              customer service center is working for you 24/7.</p>
              <hr>

              <form action="{{ route('shop.register.store') }}" method="post">
                @csrf

                <div class="mb-4">
                  <label class="form-label" for="name">邮箱</label>
                  <input type="text" name="register[email]" class="form-control" value="{{ old('register.email') }}" placeholder="邮箱地址">
                  @error('register.email')
                    <x-admin::form.error :message="$message" />
                  @enderror
                </div>

                <div class="mb-4">
                  <label class="form-label" for="name">密码</label>
                  <input type="password" name="register[password]" class="form-control" placeholder="密码">
                  @error('register.password')
                    <x-admin::form.error :message="$message" />
                  @enderror
                </div>

                <div class="mb-4">
                  <label class="form-label" for="name">确认密码</label>
                  <input type="password" name="register[password_confirmation]" class="form-control" placeholder="密码">
                  @error('register.password_confirmation')
                    <x-admin::form.error :message="$message" />
                  @enderror
                </div>

                @if (session('error'))
                  <div class="alert alert-success">
                    {{ session('error') }}
                  </div>
                @endif

                <div class="mb-4">
                  <button type="submit" class="btn btn-outline-dark"><i class="bi bi-person"></i> 注册</button>
                </div>
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
