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
    $entry_key = 'installer::installer_messages.environment.wizard.form.';
@endphp
  <div class="install-4">
    <h3 class="mb-5">系统参数配置</h3>

    <form method="post" action="{{ route('installer.environment.save') }}" novalidate class="needs-validation">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">


  {{--
      <div class="form-group {{ $errors->has('app_url') ? ' has-error ' : '' }}">
        <label for="app_url">
          {{ trans('installer::installer_messages.environment.wizard.form.app_url_label') }}
        </label>
        <input type="url" name="app_url" id="app_url" value="http://localhost"
          placeholder="{{ trans('installer::installer_messages.environment.wizard.form.app_url_placeholder') }}" />
        @if ($errors->has('app_url'))
          <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('app_url') }}
          </span>
        @endif
      </div> --}}

      {{-- <div class="form-group {{ $errors->has('database_connection') ? ' has-error ' : '' }}">
        <label for="database_connection">
          {{ trans('installer::installer_messages.environment.wizard.form.db_connection_label') }}
        </label>
        <select name="database_connection" id="database_connection">
          <option value="mysql" selected>
            {{ trans('installer::installer_messages.environment.wizard.form.db_connection_label_mysql') }}</option>
          <option value="sqlite">
            {{ trans('installer::installer_messages.environment.wizard.form.db_connection_label_sqlite') }}</option>
          <option value="pgsql">
            {{ trans('installer::installer_messages.environment.wizard.form.db_connection_label_pgsql') }}</option>
          <option value="sqlsrv">
            {{ trans('installer::installer_messages.environment.wizard.form.db_connection_label_sqlsrv') }}</option>
        </select>
        @if ($errors->has('database_connection'))
          <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('database_connection') }}
          </span>
        @endif
      </div> --}}

      {{-- <div class="form-group {{ $errors->has('database_hostname') ? ' has-error ' : '' }}">
        <label for="database_hostname">
          {{ trans('installer::installer_messages.environment.wizard.form.db_host_label') }}
        </label>
        <input type="text" name="database_hostname" id="database_hostname" value="127.0.0.1"
          placeholder="{{ trans('installer::installer_messages.environment.wizard.form.db_host_placeholder') }}" />
        @if ($errors->has('database_hostname'))
          <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('database_hostname') }}
          </span>
        @endif
      </div> --}}

      {{-- <div class="form-group {{ $errors->has('database_port') ? ' has-error ' : '' }}">
        <label for="database_port">
          {{ trans('installer::installer_messages.environment.wizard.form.db_port_label') }}
        </label>
        <input type="number" name="database_port" id="database_port" value="3306"
          placeholder="{{ trans('installer::installer_messages.environment.wizard.form.db_port_placeholder') }}" />
        @if ($errors->has('database_port'))
          <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('database_port') }}
          </span>
        @endif
      </div> --}}

      {{-- <div class="form-group {{ $errors->has('database_name') ? ' has-error ' : '' }}">
        <label for="database_name">
          {{ trans('installer::installer_messages.environment.wizard.form.db_name_label') }}
        </label>
        <input type="text" name="database_name" id="database_name" value=""
          placeholder="{{ trans('installer::installer_messages.environment.wizard.form.db_name_placeholder') }}" />
        @if ($errors->has('database_name'))
          <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('database_name') }}
          </span>
        @endif
      </div> --}}

      {{-- <div class="form-group {{ $errors->has('database_username') ? ' has-error ' : '' }}">
        <label for="database_username">
          {{ trans('installer::installer_messages.environment.wizard.form.db_username_label') }}
        </label>
        <input type="text" name="database_username" id="database_username" value=""
          placeholder="{{ trans('installer::installer_messages.environment.wizard.form.db_username_placeholder') }}" />
        @if ($errors->has('database_username'))
          <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('database_username') }}
          </span>
        @endif
      </div> --}}

      {{-- <div class="form-group {{ $errors->has('database_password') ? ' has-error ' : '' }}">
        <label for="database_password">
          {{ trans('installer::installer_messages.environment.wizard.form.db_password_label') }}
        </label>
        <input type="password" name="database_password" id="database_password" value=""
          placeholder="{{ trans('installer::installer_messages.environment.wizard.form.db_password_placeholder') }}" />
        @if ($errors->has('database_password'))
          <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('database_password') }}
          </span>
        @endif
      </div> --}}

      {{-- <div class="form-group {{ $errors->has('mail_driver') ? ' has-error ' : '' }}">
        <label for="mail_driver">
          {{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_driver_label') }}
          <sup>
            <a href="https://laravel.com/docs/5.4/mail" target="_blank"
              title="{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.more_info') }}">
              <i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>
              <span
                class="sr-only">{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.more_info') }}</span>
            </a>
          </sup>
        </label>
        <input type="text" name="mail_driver" id="mail_driver" value="smtp"
          placeholder="{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_driver_placeholder') }}" />
        @if ($errors->has('mail_driver'))
          <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('mail_driver') }}
          </span>
        @endif
      </div> --}}
      {{-- <div class="form-group {{ $errors->has('mail_host') ? ' has-error ' : '' }}">
        <label
          for="mail_host">{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_host_label') }}</label>
        <input type="text" name="mail_host" id="mail_host" value="smtp.mailtrap.io"
          placeholder="{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_host_placeholder') }}" />
        @if ($errors->has('mail_host'))
          <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('mail_host') }}
          </span>
        @endif
      </div> --}}
      {{-- <div class="form-group {{ $errors->has('mail_port') ? ' has-error ' : '' }}">
        <label
          for="mail_port">{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_port_label') }}</label>
        <input type="number" name="mail_port" id="mail_port" value="2525"
          placeholder="{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_port_placeholder') }}" />
        @if ($errors->has('mail_port'))
          <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('mail_port') }}
          </span>
        @endif
      </div> --}}
      {{-- <div class="form-group {{ $errors->has('mail_username') ? ' has-error ' : '' }}">
        <label
          for="mail_username">{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_username_label') }}</label>
        <input type="text" name="mail_username" id="mail_username" value="null"
          placeholder="{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_username_placeholder') }}" />
        @if ($errors->has('mail_username'))
          <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('mail_username') }}
          </span>
        @endif
      </div> --}}
      {{-- <div class="form-group {{ $errors->has('mail_password') ? ' has-error ' : '' }}">
        <label
          for="mail_password">{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_password_label') }}</label>
        <input type="text" name="mail_password" id="mail_password" value="null"
          placeholder="{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_password_placeholder') }}" />
        @if ($errors->has('mail_password'))
          <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('mail_password') }}
          </span>
        @endif
      </div> --}}
      {{-- <div class="form-group {{ $errors->has('mail_encryption') ? ' has-error ' : '' }}">
        <label
          for="mail_encryption">{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_encryption_label') }}</label>
        <input type="text" name="mail_encryption" id="mail_encryption" value="null"
          placeholder="{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_encryption_placeholder') }}" />
        @if ($errors->has('mail_encryption'))
          <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('mail_encryption') }}
          </span>
        @endif
      </div> --}}

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
            value="{{ old('database_hostname', '') }}" required placeholder="{{ trans($entry_key . 'db_host_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('database_hostname') ? $errors->first('database_hostname') : __('common.error_required', ['name' => trans($entry_key . 'db_host_label')]) }}</span>
        </div>


        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'db_port_label') }}
          </label>
          <input class="form-control {{ $errors->has('database_port') ? 'is-invalid' : '' }}" name="database_port"
            value="{{ old('database_port', '') }}" required placeholder="{{ trans($entry_key . 'db_host_placeholder') }}">
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
            value="{{ old('database_username', '') }}" required placeholder="{{ trans($entry_key . 'db_username_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('database_username') ? $errors->first('database_username') : __('common.error_required', ['name' => trans($entry_key . 'db_username_label')]) }}</span>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'db_password_label') }}
          </label>
          <input class="form-control" name="database_password"
            value="{{ old('database_password', '') }}" placeholder="{{ trans($entry_key . 'db_password_placeholder') }}">
        </div>


        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'app_tabs.mail_driver_label') }}
          </label>
          <input class="form-control {{ $errors->has('mail_driver') ? 'is-invalid' : '' }}" name="mail_driver"
            value="{{ old('mail_driver', 'smtp') }}" required placeholder="{{ trans($entry_key . 'app_tabs.mail_driver_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('mail_driver') ? $errors->first('mail_driver') : __('common.error_required', ['name' => trans($entry_key . 'app_tabs.mail_driver_label')]) }}</span>
        </div>


        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'app_tabs.mail_host_label') }}
          </label>
          <input class="form-control {{ $errors->has('mail_host') ? 'is-invalid' : '' }}" name="mail_host"
            value="{{ old('mail_host', '') }}" required placeholder="{{ trans($entry_key . 'app_tabs.mail_host_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('mail_host') ? $errors->first('mail_host') : __('common.error_required', ['name' => trans($entry_key . 'app_tabs.mail_host_label')]) }}</span>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'app_tabs.mail_port_label') }}
          </label>
          <input class="form-control {{ $errors->has('mail_port') ? 'is-invalid' : '' }}" name="mail_port"
            value="{{ old('mail_port', '') }}" required placeholder="{{ trans($entry_key . 'app_tabs.mail_port_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('mail_port') ? $errors->first('mail_port') : __('common.error_required', ['name' => trans($entry_key . 'app_tabs.mail_port_label')]) }}</span>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'app_tabs.mail_username_label') }}
          </label>
          <input class="form-control {{ $errors->has('mail_username') ? 'is-invalid' : '' }}" name="mail_username"
            value="{{ old('mail_username', '') }}" required placeholder="{{ trans($entry_key . 'app_tabs.mail_username_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('mail_username') ? $errors->first('mail_username') : __('common.error_required', ['name' => trans($entry_key . 'app_tabs.mail_username_label')]) }}</span>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'app_tabs.mail_password_label') }}
          </label>
          <input class="form-control {{ $errors->has('mail_password') ? 'is-invalid' : '' }}" name="mail_password"
            value="{{ old('mail_password', '') }}" type="password" required placeholder="{{ trans($entry_key . 'app_tabs.mail_password_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('mail_password') ? $errors->first('mail_password') : __('common.error_required', ['name' => trans($entry_key . 'app_tabs.mail_password_label')]) }}</span>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'app_tabs.mail_encryption_label') }}
          </label>
          <input class="form-control {{ $errors->has('mail_encryption') ? 'is-invalid' : '' }}" name="mail_encryption"
            value="{{ old('mail_encryption', '') }}" type="password" required placeholder="{{ trans($entry_key . 'app_tabs.mail_encryption_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('mail_encryption') ? $errors->first('mail_encryption') : __('common.error_required', ['name' => trans($entry_key . 'app_tabs.mail_encryption_label')]) }}</span>
        </div>


        <div class="col-sm-12 my-5">
          <div class="d-flex justify-content-end">
            <button class="btn btn-primary d-flex align-items-center" type="submit">
              {{ trans('installer::installer_messages.environment.wizard.form.buttons.install') }}
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
