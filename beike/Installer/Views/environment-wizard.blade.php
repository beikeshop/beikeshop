@extends('installer::layouts.master')

@section('template_title')
    {{ trans('installer::installer_messages.environment.wizard.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-magic fa-fw" aria-hidden="true"></i>
    {!! trans('installer::installer_messages.environment.wizard.title') !!}
@endsection

@section('container')
    <form method="post" action="{{ route('installer.environment.save') }}" class="tabs-wrap">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-group {{ $errors->has('app_url') ? ' has-error ' : '' }}">
            <label for="app_url">
                {{ trans('installer::installer_messages.environment.wizard.form.app_url_label') }}
            </label>
            <input type="url" name="app_url" id="app_url" value="http://localhost" placeholder="{{ trans('installer::installer_messages.environment.wizard.form.app_url_placeholder') }}" />
            @if ($errors->has('app_url'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('app_url') }}
                </span>
            @endif
        </div>

        <div class="form-group {{ $errors->has('database_connection') ? ' has-error ' : '' }}">
            <label for="database_connection">
                {{ trans('installer::installer_messages.environment.wizard.form.db_connection_label') }}
            </label>
            <select name="database_connection" id="database_connection">
                <option value="mysql" selected>{{ trans('installer::installer_messages.environment.wizard.form.db_connection_label_mysql') }}</option>
                <option value="sqlite">{{ trans('installer::installer_messages.environment.wizard.form.db_connection_label_sqlite') }}</option>
                <option value="pgsql">{{ trans('installer::installer_messages.environment.wizard.form.db_connection_label_pgsql') }}</option>
                <option value="sqlsrv">{{ trans('installer::installer_messages.environment.wizard.form.db_connection_label_sqlsrv') }}</option>
            </select>
            @if ($errors->has('database_connection'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('database_connection') }}
                </span>
            @endif
        </div>

        <div class="form-group {{ $errors->has('database_hostname') ? ' has-error ' : '' }}">
            <label for="database_hostname">
                {{ trans('installer::installer_messages.environment.wizard.form.db_host_label') }}
            </label>
            <input type="text" name="database_hostname" id="database_hostname" value="127.0.0.1" placeholder="{{ trans('installer::installer_messages.environment.wizard.form.db_host_placeholder') }}" />
            @if ($errors->has('database_hostname'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('database_hostname') }}
                </span>
            @endif
        </div>

        <div class="form-group {{ $errors->has('database_port') ? ' has-error ' : '' }}">
            <label for="database_port">
                {{ trans('installer::installer_messages.environment.wizard.form.db_port_label') }}
            </label>
            <input type="number" name="database_port" id="database_port" value="3306" placeholder="{{ trans('installer::installer_messages.environment.wizard.form.db_port_placeholder') }}" />
            @if ($errors->has('database_port'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('database_port') }}
                </span>
            @endif
        </div>

        <div class="form-group {{ $errors->has('database_name') ? ' has-error ' : '' }}">
            <label for="database_name">
                {{ trans('installer::installer_messages.environment.wizard.form.db_name_label') }}
            </label>
            <input type="text" name="database_name" id="database_name" value="" placeholder="{{ trans('installer::installer_messages.environment.wizard.form.db_name_placeholder') }}" />
            @if ($errors->has('database_name'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('database_name') }}
                </span>
            @endif
        </div>

        <div class="form-group {{ $errors->has('database_username') ? ' has-error ' : '' }}">
            <label for="database_username">
                {{ trans('installer::installer_messages.environment.wizard.form.db_username_label') }}
            </label>
            <input type="text" name="database_username" id="database_username" value="" placeholder="{{ trans('installer::installer_messages.environment.wizard.form.db_username_placeholder') }}" />
            @if ($errors->has('database_username'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('database_username') }}
                </span>
            @endif
        </div>

        <div class="form-group {{ $errors->has('database_password') ? ' has-error ' : '' }}">
            <label for="database_password">
                {{ trans('installer::installer_messages.environment.wizard.form.db_password_label') }}
            </label>
            <input type="password" name="database_password" id="database_password" value="" placeholder="{{ trans('installer::installer_messages.environment.wizard.form.db_password_placeholder') }}" />
            @if ($errors->has('database_password'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('database_password') }}
                </span>
            @endif
        </div>

        <div class="form-group {{ $errors->has('mail_driver') ? ' has-error ' : '' }}">
            <label for="mail_driver">
                {{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_driver_label') }}
                <sup>
                    <a href="https://laravel.com/docs/5.4/mail" target="_blank" title="{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.more_info') }}">
                        <i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>
                        <span class="sr-only">{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.more_info') }}</span>
                    </a>
                </sup>
            </label>
            <input type="text" name="mail_driver" id="mail_driver" value="smtp" placeholder="{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_driver_placeholder') }}" />
            @if ($errors->has('mail_driver'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('mail_driver') }}
                </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('mail_host') ? ' has-error ' : '' }}">
            <label for="mail_host">{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_host_label') }}</label>
            <input type="text" name="mail_host" id="mail_host" value="smtp.mailtrap.io" placeholder="{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_host_placeholder') }}" />
            @if ($errors->has('mail_host'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('mail_host') }}
                </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('mail_port') ? ' has-error ' : '' }}">
            <label for="mail_port">{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_port_label') }}</label>
            <input type="number" name="mail_port" id="mail_port" value="2525" placeholder="{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_port_placeholder') }}" />
            @if ($errors->has('mail_port'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('mail_port') }}
                </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('mail_username') ? ' has-error ' : '' }}">
            <label for="mail_username">{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_username_label') }}</label>
            <input type="text" name="mail_username" id="mail_username" value="null" placeholder="{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_username_placeholder') }}" />
            @if ($errors->has('mail_username'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('mail_username') }}
                </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('mail_password') ? ' has-error ' : '' }}">
            <label for="mail_password">{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_password_label') }}</label>
            <input type="text" name="mail_password" id="mail_password" value="null" placeholder="{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_password_placeholder') }}" />
            @if ($errors->has('mail_password'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('mail_password') }}
                </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('mail_encryption') ? ' has-error ' : '' }}">
            <label for="mail_encryption">{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_encryption_label') }}</label>
            <input type="text" name="mail_encryption" id="mail_encryption" value="null" placeholder="{{ trans('installer::installer_messages.environment.wizard.form.app_tabs.mail_encryption_placeholder') }}" />
            @if ($errors->has('mail_encryption'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('mail_encryption') }}
                </span>
            @endif
        </div>
        <button class="button" type="submit">
            {{ trans('installer::installer_messages.environment.wizard.form.buttons.install') }}
            <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
        </button>
    </form>
@endsection

@section('scripts')
    <script type="text/javascript">
        function checkEnvironment(val) {
            var element=document.getElementById('environment_text_input');
            if(val=='other') {
                element.style.display='block';
            } else {
                element.style.display='none';
            }
        }
        function showDatabaseSettings() {
            document.getElementById('tab2').checked = true;
        }
        function showApplicationSettings() {
            document.getElementById('tab3').checked = true;
        }
    </script>
@endsection
