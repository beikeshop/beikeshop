@extends('installer::layouts.master')

@section('template_title')
  {{ trans('installer::installer_messages.environment.wizard.templateTitle') }}
@endsection

@section('title')
  <i class="fa fa-magic fa-fw" aria-hidden="true"></i>
  {!! trans('installer::installer_messages.environment.wizard.title') !!}
@endsection

@section('content')
@php
    $entry_key = 'installer::installer_messages.environment.';
@endphp
  <div class="install-4">
    <h3 class="mb-5">{{ __('installer::installer_messages.environment.title') }}</h3>

    <form method="post" action="{{ route('installer.environment.save') }}" novalidate class="needs-validation">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="row gx-4 gy-3">
        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'app_url_label') }}
          </label>
          <input class="form-control {{ $errors->has('app_url') ? 'is-invalid' : '' }}" name="app_url"
            value="{{ old('app_url', '') }}" required placeholder="{{ trans($entry_key . 'app_url_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('app_url') ? $errors->first('app_url') : __('common.error_required', ['name' => trans($entry_key . 'app_url_label')]) }}</span>
        </div>
        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'db_connection_label') }}
          </label>
          <select name="database_connection" class="form-select" aria-label="Default select example">
            <option value="mysql" selected>
              {{ trans($entry_key . 'db_connection_label_mysql') }}</option>
            <option value="sqlite">
              {{ trans($entry_key . 'db_connection_label_sqlite') }}</option>
            <option value="pgsql">
              {{ trans($entry_key . 'db_connection_label_pgsql') }}</option>
            <option value="sqlsrv">
              {{ trans($entry_key . 'db_connection_label_sqlsrv') }}</option>
          </select>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'db_host_label') }}
          </label>
          <input class="form-control {{ $errors->has('database_hostname') ? 'is-invalid' : '' }}" name="database_hostname"
            value="{{ old('database_hostname', '127.0.0.1') }}" required placeholder="{{ trans($entry_key . 'db_host_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('database_hostname') ? $errors->first('database_hostname') : __('common.error_required', ['name' => trans($entry_key . 'db_host_label')]) }}</span>
        </div>


        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'db_port_label') }}
          </label>
          <input class="form-control {{ $errors->has('database_port') ? 'is-invalid' : '' }}" name="database_port"
            value="{{ old('database_port', '3306') }}" required placeholder="{{ trans($entry_key . 'db_host_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('database_port') ? $errors->first('database_port') : __('common.error_required', ['name' => trans($entry_key . 'db_port_label')]) }}</span>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'db_name_label') }}
          </label>
          <input class="form-control {{ $errors->has('database_name') ? 'is-invalid' : '' }}" name="database_name"
            value="{{ old('database_name', '') }}" required placeholder="{{ trans($entry_key . 'db_name_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('database_name') ? $errors->first('database_name') : __('common.error_required', ['name' => trans($entry_key . 'db_name_label')]) }}</span>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'db_username_label') }}
          </label>
          <input class="form-control {{ $errors->has('database_username') ? 'is-invalid' : '' }}" name="database_username"
            value="{{ old('database_username', 'root') }}" required placeholder="{{ trans($entry_key . 'db_username_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('database_username') ? $errors->first('database_username') : __('common.error_required', ['name' => trans($entry_key . 'db_username_label')]) }}</span>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'db_password_label') }}
          </label>
          <input class="form-control {{ $errors->has('database_password') ? 'is-invalid' : '' }}" name="database_password"
            value="{{ old('database_password', '') }}" required placeholder="{{ trans($entry_key . 'db_password_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('database_password') ? $errors->first('database_password') : __('common.error_required', ['name' => trans($entry_key . 'db_password_label')]) }}</span>
        </div>


        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'admin_email') }}
          </label>
          <input class="form-control {{ $errors->has('admin_email') ? 'is-invalid' : '' }}" name="admin_email"
            value="{{ old('admin_email', '') }}" required placeholder="{{ trans($entry_key . 'admin_email') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('admin_email') ? $errors->first('admin_email') : __('common.error_required', ['name' => trans($entry_key . 'admin_email')]) }}</span>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'admin_password') }}
          </label>
          <input class="form-control {{ $errors->has('admin_password') ? 'is-invalid' : '' }}" type="password" name="admin_password" name="admin_password"
            value="{{ old('admin_password', '') }}" required placeholder="{{ trans($entry_key . 'admin_password') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('admin_password') ? $errors->first('admin_password') : __('common.error_required', ['name' => trans($entry_key . 'admin_password')]) }}</span>
        </div>


        <div class="col-sm-12 my-5">
          <div class="d-flex justify-content-end">
            <button class="btn btn-primary d-flex align-items-center" type="submit">
              {{ trans('installer::installer_messages.environment.install') }}
              <i class="bi bi-arrow-right-short fs-2 lh-1 ms-2"></i>
            </button>
          </div>
        </div>



      </div>
    </form>
  </div>
@endsection

@section('scripts')
  <script type="text/javascript">
  // Example starter JavaScript for disabling form submissions if there are invalid fields
  $(function () {
    var forms = document.querySelectorAll(".needs-validation");

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms).forEach(function (form) {
      form.addEventListener(
        "submit",
        function (event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }

          form.classList.add("was-validated");
        },
        false
      );
    });
  });
  </script>
@endsection
