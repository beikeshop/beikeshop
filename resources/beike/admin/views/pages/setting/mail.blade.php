@extends('admin::layouts.master')

@section('title', __('admin/common.settings.mail'))

@section('content-area-class', 'w-max-1200')

@section('page-title-back', admin_route('settings.index'))

@section('head-form-btns', true)

@section('content')
  @if (session('success'))
    <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
  @endif
  @if (session('error'))
    <div class="alert alert-danger">
      {!! session('error') !!}
    </div>
  @endif
  <div class="card h-min-600">
    <div class="card-body">
      @hook('admin.setting.mail.content.before')
      <form action="{{ admin_route('settings.store') }}" class="needs-validation" novalidate method="POST" id="form-app">
        @csrf
        <input type="hidden" name="return_url" value="{{ url()->full() }}"/>

        @hook('admin.setting.mail.before')

        <x-admin-form-switch name="use_queue" title="{{ __('admin/setting.use_queue') }}" value="{{ old('use_queue', system_setting('base.use_queue', '0')) }}">
          <div class="help-text font-size-12 lh-base">{{__('admin/setting.use_queue_text')}}https://docs.beikeshop.com/config/queue.html</div>
        </x-admin-form-switch>
        <x-admin::form.row title="{{ __('admin/setting.mail_engine') }}">
          <select name="mail_engine" v-model="mail_engine" class="form-select wp-200 me-3">
            <option :value="item.code" v-for="item, index in source.mailEngines" :key="index">@{{ item.name }}</option>
          </select>
          <div v-if="mail_engine == 'log'" class="help-text font-size-12 lh-base">{{ __('admin/setting.mail_log') }}</div>
        </x-admin::form.row>

        <div v-if="mail_engine == 'smtp'">
          <x-admin-form-input name="smtp[host]" required title="{{ __('admin/setting.smtp_host') }}" value="{{ old('host', system_setting('base.smtp.host', '')) }}">
          </x-admin-form-input>
          <x-admin-form-input name="smtp[username]" required title="{{ __('admin/setting.smtp_username') }}" value="{{ old('username', system_setting('base.smtp.username', '')) }}">
          </x-admin-form-input>
          <x-admin-form-input name="smtp[password]" type="password" required title="{{ __('admin/setting.smtp_password') }}" value="{{ old('password', system_setting('base.smtp.password', '')) }}">
            <div class="help-text font-size-12 lh-base">{{ __('admin/setting.smtp_password_info') }}</div>
          </x-admin-form-input>
          <x-admin-form-input name="smtp[encryption]" required title="{{ __('admin/setting.smtp_encryption') }}" value="{{ old('encryption', system_setting('base.smtp.encryption', 'TLS')) }}">
            <div class="help-text font-size-12 lh-base">{{ __('admin/setting.smtp_encryption_info') }}</div>
          </x-admin-form-input>
          <x-admin-form-input name="smtp[port]" required title="{{ __('admin/setting.smtp_port') }}" value="{{ old('port', system_setting('base.smtp.port', '465')) }}">
          </x-admin-form-input>
          <x-admin-form-input name="smtp[timeout]" required title="{{ __('admin/setting.smtp_timeout') }}" value="{{ old('timeout', system_setting('base.smtp.timeout', '5')) }}">
          </x-admin-form-input>
        </div>

        <div v-if="mail_engine == 'sendmail'">
          <x-admin-form-input name="sendmail[path]" :placeholder="222" required title="{{ __('admin/setting.sendmail_path') }}" value="{{ old('path', system_setting('base.sendmail.path', '')) }}">
            <div class="help-text font-size-12 lh-base">系统 sendmail 执行路径, 一般为 /usr/sbin/sendmail -bs</div>
          </x-admin-form-input>
        </div>

        <div v-if="mail_engine == 'mailgun'">
          <x-admin-form-input name="mailgun[domain]" required title="{{ __('admin/setting.mailgun_domain') }}" value="{{ old('domain', system_setting('base.mailgun.domain', '')) }}">
          </x-admin-form-input>
          <x-admin-form-input name="mailgun[secret]" required title="{{ __('admin/setting.mailgun_secret') }}" value="{{ old('secret', system_setting('base.mailgun.secret', '')) }}">
          </x-admin-form-input>
          <x-admin-form-input name="mailgun[endpoint]" required title="{{ __('admin/setting.mailgun_endpoint') }}" value="{{ old('endpoint', system_setting('base.mailgun.endpoint', '')) }}">
          </x-admin-form-input>
        </div>

        <div v-if="mail_engine == 'sendcloud'">
          <x-admin-form-input name="sendcloud[api_user]" required title="{{ __('admin/setting.sendcloud_api_user') }}" value="{{ old('api_user', system_setting('base.sendcloud.api_user', '')) }}">
            <div class="help-text font-size-12 lh-base">{{ __('admin/setting.sendcloud_api_user_info') }}</div>
          </x-admin-form-input>
          <x-admin-form-input name="sendcloud[api_key]" type="password" required title="{{ __('admin/setting.sendcloud_api_key') }}" value="{{ old('api_key', system_setting('base.sendcloud.api_key', '')) }}">
            <div class="help-text font-size-12 lh-base">{{ __('admin/setting.sendcloud_api_key_info') }}</div>
          </x-admin-form-input>
          <x-admin-form-input name="sendcloud[endpoint]" title="{{ __('admin/setting.sendcloud_endpoint') }}" value="{{ old('endpoint', system_setting('base.sendcloud.endpoint', 'https://api.sendcloud.net')) }}">
            <div class="help-text font-size-12 lh-base">{{ __('admin/setting.sendcloud_endpoint_info') }}</div>
          </x-admin-form-input>
        </div>

        <div v-if="mail_engine == 'sendgrid'">
          <x-admin-form-input name="sendgrid[api_key]" type="password" required title="{{ __('admin/setting.sendgrid_api_key') }}" value="{{ old('api_key', system_setting('base.sendgrid.api_key', '')) }}">
            <div class="help-text font-size-12 lh-base">{{ __('admin/setting.sendgrid_api_key_info') }}</div>
          </x-admin-form-input>
          <x-admin-form-input name="sendgrid[endpoint]" title="{{ __('admin/setting.sendgrid_endpoint') }}" value="{{ old('endpoint', system_setting('base.sendgrid.endpoint', 'https://api.sendgrid.com')) }}">
            <div class="help-text font-size-12 lh-base">{{ __('admin/setting.sendgrid_endpoint_info') }}</div>
          </x-admin-form-input>
        </div>

        <div v-if="mail_engine != ''">
          <x-admin::form.row :title="__('admin/setting.email_send_admin')">
            <div class="input-group wp-400">
              <input name="mail_alert[]" type="hidden" value="">
              <div class="form-check mt-2 me-3">
                <input class="form-check-input" type="checkbox" id="check-input-register" name="mail_alert[]" value="register" {{ in_array('register', old('mail_alert', system_setting('base.mail_alert', []))) ? 'checked' : '' }}>
                <label class="form-check-label" for="check-input-register">{{ __('admin/setting.email_type_register') }}</label>
              </div>
              <div class="form-check mt-2 me-3">
                <input class="form-check-input" type="checkbox" id="check-input-order" name="mail_alert[]" value="order" {{ in_array('order', old('mail_alert', system_setting('base.mail_alert', []))) ? 'checked' : '' }}>
                <label class="form-check-label" for="check-input-order">{{ __('admin/setting.email_type_order') }}</label>
              </div>
              <div class="form-check mt-2 me-3">
                <input class="form-check-input" type="checkbox" id="check-input-return" name="mail_alert[]" value="return" {{ in_array('return', old('mail_alert', system_setting('base.mail_alert', []))) ? 'checked' : '' }}>
                <label class="form-check-label" for="check-input-return">{{ __('admin/setting.email_type_return') }}</label>
              </div>
            </div>
          </x-admin::form.row>
          <x-admin::form.row :title="__('admin/setting.email_send_customer')">
            <input name="mail_customer[]" type="hidden" value="">
            <div class="input-group wp-400">
              <div class="form-check mt-2 me-3">
                <input class="form-check-input" type="checkbox" id="check-input-customer-register" name="mail_customer[]" value="register" {{ in_array('register', old('mail_customer', system_setting('base.mail_customer', []))) ? 'checked' : '' }}>
                <label class="form-check-label" for="check-input-customer-register">{{ __('admin/setting.email_type_register') }}</label>
              </div>
              <div class="form-check mt-2 me-3">
                <input class="form-check-input" type="checkbox" id="check-input-customer-order" name="mail_customer[]" value="order" {{ in_array('order', old('mail_customer', system_setting('base.mail_customer', []))) ? 'checked' : '' }}>
                <label class="form-check-label" for="check-input-customer-order">{{ __('admin/setting.email_type_order') }}</label>
              </div>
              <div class="form-check mt-2 me-3">
                <input class="form-check-input" type="checkbox" id="check-input-customer-return" name="mail_customer[]" value="return" {{ in_array('return', old('mail_customer', system_setting('base.mail_customer', []))) ? 'checked' : '' }}>
                <label class="form-check-label" for="check-input-customer-return">{{ __('admin/setting.email_type_return') }}</label>
              </div>
            </div>
            <div class="help-text lh-base smtp-qq-hint d-none">{{ __('admin/setting.smtp_qq_hint') }}</div>
          </x-admin::form.row>
        </div>

        @hook('admin.setting.mail.after')

        <x-admin::form.row title="">
          <button type="submit" class="btn btn-primary d-none mt-4">{{ __('common.submit') }}</button>
        </x-admin::form.row>
      </form>
      @hook('admin.setting.mail.content.after')
    </div>
  </div>
@endsection

@push('footer')
  <script>
    let app = new Vue({
      el: '#form-app',
      data: {
        mail_engine: @json(old('mail_engine', system_setting('base.mail_engine', ''))),

        source: {
          mailEngines: [
            {name: '{{ __('admin/builder.text_no') }}', code: ''},
            {name: 'SMTP', code: 'smtp'},
            {name: 'Sendmail', code: 'sendmail'},
            {name: 'Mailgun', code: 'mailgun'},
            {name: 'SendCloud', code: 'sendcloud'},
            // {name: 'SendGrid', code: 'sendgrid'},
            {name: 'Log', code: 'log'},
          ]
        },
      },
    });
  </script>
@endpush