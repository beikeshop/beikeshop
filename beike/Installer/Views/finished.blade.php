@extends('installer::layouts.master')

@section('template_title')
  {{ trans('installer::installer_messages.final.templateTitle') }}
@endsection

@section('title')
  <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
  {{ __('installer::installer_messages.final.title') }}
@endsection

@section('content')
  <div class="install-2">
		<h3 class="mb-5">{{ __('installer::installer_messages.final.title') }}</h3>

		<div class="d-flex justify-content-center flex-column align-items-center">

			<div class="welcome-img mb-5" style="max-width: 260px;"><img src="https://beikeshop.com/install/install-2.png?version={{ config('beike.version') }}&build_date={{ config('beike.build') }}" class="img-fluid"></div>

			<h5 class="text-center mb-5">{{ __('installer::installer_messages.final.finished') }}</h5>

			<div class="d-flex justify-content-center">
				<a href="{{ url('/') }}" class="btn btn-primary"><i class="bi bi-window-dock me-2"></i> {{ trans('installer::installer_messages.final.to_front') }}</a>
				<a href="{{ url('/admin') }}" class="btn btn-outline-primary ms-3"><i class="bi bi-window-sidebar me-2"></i> {{ trans('installer::installer_messages.final.to_admin') }}</a>
			</div>
    </div>
  </div>
@endsection
