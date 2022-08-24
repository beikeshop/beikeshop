@extends('installer::layouts.master')

@section('template_title')
  {{ trans('installer::installer_messages.final.templateTitle') }}
@endsection

@section('title')
  <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
  {{-- {{ trans('installer::installer_messages.final.title') }} --}}
@endsection

@section('content')
  <div class="install-2">
		<h3 class="mb-5">安装结果</h3>

    {{-- @if (session('message')['dbOutputLog'])
      <p><strong><small>{{ trans('installer::installer_messages.final.migration') }}</small></strong></p>
      <pre><code>{{ session('message')['dbOutputLog'] }}</code></pre>
    @endif

    <p><strong><small>{{ trans('installer::installer_messages.final.console') }}</small></strong></p>
    <pre><code>{{ $finalMessages }}</code></pre>

    <p><strong><small>{{ trans('installer::installer_messages.final.log') }}</small></strong></p>
    <pre><code>{{ $finalStatusMessage }}</code></pre>

    <p><strong><small>{{ trans('installer::installer_messages.final.env') }}</small></strong></p>
    <pre><code>{{ $finalEnvFile }}</code></pre> --}}

		<div class="d-flex justify-content-center flex-column align-items-center">

			<div class="welcome-img mb-5" style="max-width: 260px;"><img src="{{ asset('/install/image/install-2.png') }}" class="img-fluid"></div>

			<h5 class="text-center mb-5">恭喜你，系统安装成功，赶快体验吧</h5>

			<div class="d-flex justify-content-center">
				<a href="{{ url('/') }}" class="btn btn-primary">{{ trans('installer::installer_messages.final.exit') }}</a>
			</div>
    </div>
  </div>
@endsection
