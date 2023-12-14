@extends('layout.master')

@section('body-class', 'page-account-password')

@section('content')
  <x-shop-breadcrumb type="static" value="account.password.index" />

  <div class="container" id="address-app">
    <div class="row">
      <x-shop-sidebar />

      <div class="col-12 col-md-9">
        <div class="card h-min-600">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">{{ __('shop/account/password.index') }}</h5>
          </div>
          <div class="card-body h-600">
            <form novalidate class="needs-validation" action="{{ shop_route('account.password.update') }}" method="POST">
              @csrf
              {{ method_field('POST') }}

              @if (session('success'))
                <x-shop-alert type="success" msg="{{ session('success') }}" class="mt-4" />
              @endif

              <div class="row gx-4 gy-3 w-50">
                <div class="col-sm-12">
                  <label class="form-label">{{ __('shop/account/password.old_password') }}</label>
                  <input class="form-control {{ $errors->has('old_password') ? 'is-invalid' : '' }}" type="text" name="old_password"
                    value="{{ old('old_password', $customer->old_password ?? '') }}" required>
                  <span class="invalid-feedback"
                    role="alert">{{ $errors->has('old_password') ? $errors->first('old_password') : __('common.error_required', ['name' => __('shop/account/password.old_password')]) }}</span>
                </div>
                <div class="col-sm-12">
                  <label class="form-label">{{ __('shop/account/password.new_password') }}</label>
                  <input class="form-control" type="password" name="new_password"
                    value="{{ old('new_password', $customer->new_password ?? '') }}" required>
                </div>
                <div class="col-sm-12">
                  <label class="form-label">{{ __('shop/account/password.new_password_confirmation') }}</label>
                  <input class="form-control {{ $errors->has('new_password') ? 'is-invalid' : '' }}" type="password" name="new_password_confirmation"
                    value="{{ old('new_password_confirmation', $customer->new_password_confirmation ?? '') }}" required>
                  <span class="invalid-feedback"
                    role="alert">{{ $errors->has('new_password') ? $errors->first('new_password') : __('common.error_required', ['name' => __('shop/account/password.new_password')]) }}</span>
                </div>

                <div class="col-12 mt-4">
                  <button class="btn btn-primary mt-sm-0" type="submit">{{ __('common.submit') }}</button>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('add-scripts')
  <script>

  </script>
@endpush


