@extends('installer::layouts.master')

@section('template_title')
  {{ trans('installer::installer_messages.environment.template_title') }}
@endsection

@section('title')
  <i class="fa fa-magic fa-fw" aria-hidden="true"></i>
  {!! trans('installer::installer_messages.environment.title') !!}
@endsection

@section('content')
  @php
    $entry_key = 'installer::installer_messages.environment.';
  @endphp
  <div class="install-4">
    <h3 class="mb-5">{{ __('installer::installer_messages.environment.title') }}</h3>

    <form method="post" id="environment-form" action="{{ route('installer.environment.save') }}" novalidate class="needs-validation">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="row gx-4 gy-3 mb-5 database-link-wrap">
        <div class="col-12">
          <div class="d-flex align-items-center title-status">
            <h5 class="mb-0">{{ __('installer::installer_messages.environment.database_link') }}</h5>
            <div class="d-none ms-3 database-loading">
              <div class="spinner-border spinner-border-sm text-muted"></div>
              <span class="text-muted">{{ __('installer::installer_messages.environment.ajax_database_parameters') }}</span>
            </div>
            <div class="text-danger d-flex align-items-center ms-3 d-none">
              <i class="bi bi-x-circle-fill fs-5 lh-1 me-1"></i><span></span>
            </div>
            <div class="text-success d-flex align-items-center ms-3 d-none"><i
                class="bi bi-check-circle-fill me-1 fs-4 lh-1"></i>{{ __('installer::installer_messages.environment.ajax_database_success') }}</div>
          </div>
          <hr class="mb-0">
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'db_connection_label') }}
          </label>
          <select name="database_connection" class="form-select" aria-label="Default select example">
            <option value="mysql" selected>
              {{ trans($entry_key . 'db_connection_label_mysql') }}</option>
          </select>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'db_host_label') }}
          </label>
          <input class="form-control {{ $errors->has('database_hostname') ? 'is-invalid' : '' }}" name="database_hostname"
            value="{{ old('database_hostname', '127.0.0.1') }}" required
            placeholder="{{ trans($entry_key . 'db_host_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('database_hostname') ? $errors->first('database_hostname') : __('common.error_required', ['name' => trans($entry_key . 'db_host_label')]) }}</span>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'db_port_label') }}
          </label>
          <input class="form-control {{ $errors->has('database_port') ? 'is-invalid' : '' }}" name="database_port"
            value="{{ old('database_port', '3306') }}" required
            placeholder="{{ trans($entry_key . 'db_host_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('database_port') ? $errors->first('database_port') : __('common.error_required', ['name' => trans($entry_key . 'db_port_label')]) }}</span>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'db_name_label') }}
          </label>
          <input class="form-control {{ $errors->has('database_name') ? 'is-invalid' : '' }}" name="database_name"
            value="{{ old('database_name', '') }}" required
            placeholder="{{ trans($entry_key . 'db_name_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('database_name') ? $errors->first('database_name') : __('common.error_required', ['name' => trans($entry_key . 'db_name_label')]) }}</span>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'db_username_label') }}
          </label>
          <input class="form-control {{ $errors->has('database_username') ? 'is-invalid' : '' }}"
            name="database_username" value="{{ old('database_username', 'root') }}" required
            placeholder="{{ trans($entry_key . 'db_username_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('database_username') ? $errors->first('database_username') : __('common.error_required', ['name' => trans($entry_key . 'db_username_label')]) }}</span>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'db_password_label') }}
          </label>
          <input class="form-control {{ $errors->has('database_password') ? 'is-invalid' : '' }}"
            name="database_password" value="{{ old('database_password', '') }}"
            placeholder="{{ trans($entry_key . 'db_password_placeholder') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('database_password') ? $errors->first('database_password') : __('common.error_required', ['name' => trans($entry_key . 'db_password_label')]) }}</span>
        </div>

      </div>

      <div class="row gx-4 gy-3 admin-data-wrap d-none">
        <div class="col-12">
          <h5>{{ __('installer::installer_messages.environment.admin_info') }}</h5>
          <hr class="mb-0">
        </div>
        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'admin_email') }}
          </label>
          <input class="form-control {{ $errors->has('admin_email') ? 'is-invalid' : '' }}" name="admin_email"
            value="{{ old('admin_email', 'root@guangda.work') }}" type="email" required placeholder="{{ trans($entry_key . 'admin_email') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('admin_email') ? $errors->first('admin_email') : __('installer::installer_messages.environment.error_email') }}</span>
        </div>

        <div class="col-sm-6">
          <label class="form-label">
            {{ trans($entry_key . 'admin_password') }}
          </label>
          <input class="form-control {{ $errors->has('admin_password') ? 'is-invalid' : '' }}" name="admin_password"
            name="admin_password" value="{{ old('admin_password', '') }}" required
            placeholder="{{ trans($entry_key . 'admin_password') }}">
          <span class="invalid-feedback"
            role="alert">{{ $errors->has('admin_password') ? $errors->first('admin_password') : __('common.error_required', ['name' => trans($entry_key . 'admin_password')]) }}</span>
        </div>

        <div class="col-sm-12 my-5">
          <div class="d-flex justify-content-end">
            <button class="btn btn-primary d-flex align-items-center" id="submit-button" type="submit">
              <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
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
    $(function() {
      $('input[name="admin_password"]').val(randomString(16));

      $('.database-link-wrap input').on('keyup', function() {
        if (isDatabaseInputVal()) {
          debounce(getDatabaseStatus, 500)
        }
      })

      if ($('input[name="admin_email"]').hasClass('is-invalid')) {
        $('.admin-data-wrap').removeClass('d-none')
      }

      if ($('.content > .alert-danger').length) {
        getDatabaseStatus()
      }

      // $('#submit-button').click(function() {
      //   setTimeout(() => {
      //     $(this).prop('disabled', true).addClass('text-white').find('span').removeClass('d-none')
      //   }, 0);
      // })
    })

    // Example starter JavaScript for disabling form submissions if there are invalid fields
    $(function() {
      var forms = document.querySelectorAll(".needs-validation");

      // Loop over them and prevent submission
      Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener("submit", function(event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          } else {
            $("#submit-button").prop('disabled', true).addClass('text-white').find('span').removeClass('d-none')
          }

          form.classList.add("was-validated");
        }, false);
      });
    });

    function randomString(length) {
      let str = '';
      for (; str.length < length; str += Math.random().toString(36).substr(2));
      return str.substr(0, length);
    }

    function isDatabaseInputVal() {
      let isDate = true;
      $('.database-link-wrap input').each((index, el) => {
        if ($(el).val() == '' && $(el).prop('name') != 'database_password') {
          isDate = false;
        }
      })

      return isDate
    }

    let _debounceTimeout = null;

    function debounce(fn, delay = 500) {
      clearTimeout(_debounceTimeout);
      _debounceTimeout = setTimeout(() => {
        fn();
      }, delay);
    }

    function getDatabaseStatus() {
      const self = this;
      $('.database-link-wrap input').removeClass('is-invalid')

      $.ajax({
        url: 'environment/validate_db',
        type: 'POST',
        data: $('form :input[name!="admin_email"][name!="admin_password"]').serialize(),
        beforeSend: function () {
          $('.database-loading').removeClass('d-none')
          $('.title-status .text-success, .title-status .text-danger').addClass('d-none')
        },
        complete: function() {
          $('.database-loading').addClass('d-none');
        },
        success: function(json) {
          $('.database-link-wrap input').addClass('is-valid')
          $('.title-status .text-success').removeClass('d-none')
          $('.admin-data-wrap').removeClass('d-none')
        },
        error: function(json) {
          json = json.responseJSON;
          var data = Object.keys(json.data);

          data.forEach((e)=> {
            $('.database-link-wrap input[name="' + e + '"]').addClass('is-invalid').next('.invalid-feedback').text(json.data[e])
          })

          if (json.data.database_version) {
            $('.title-status .text-danger').removeClass('d-none').find('span').text(json.data.database_version);
          }

          if (json.data.database_other) {
            $('.title-status .text-danger').removeClass('d-none').find('span').text(json.data.database_other);
          }

          $('.admin-data-wrap').addClass('d-none')
        }
      });
    }
  </script>
@endsection
