@extends('installer::layouts.master')

@section('template_title')
    {{ trans('installer::installer_messages.welcome.template_title') }}
@endsection

@section('title')
    {{ trans('installer::installer_messages.welcome.title') }}
@endsection

@section('content')
  <div class="install-1">
    <div class="d-flex align-items-center justify-content-between">
      <h3 class="">{{ __('installer::installer_messages.welcome.title') }}</h3>
        <div class="dropdown">
          <a class="btn btn-outline-primary btn-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            @foreach ($languages as $key => $language)
              @if ($locale == $key)
                {{ $language }}
              @endif
            @endforeach
          </a>

          <ul class="dropdown-menu">
            @foreach ($languages as $key => $language)
              <li><a class="dropdown-item" href="{{ route('installer.lang.switch', [$key]) }}">{{ $language }}</a></li>
            @endforeach
          </ul>
        </div>
    </div>
    <div class="content-main d-flex justify-content-center align-items-center flex-column">
      <div class="welcome-img"><img src="{{ asset('/install/image/install-1.png') }}" class="img-fluid"></div>
      <h5 class="mb-5 text-muted fw-3 fw-normal guide-text text-center lh-base">{{ __('installer::installer_messages.welcome.describe') }}</h5>

      <p class="text-center">
        <a href="{{ route('installer.requirements') }}" class="btn btn-primary d-flex align-items-center">
          {{ trans('installer::installer_messages.welcome.next') }}
          <i class="bi bi-arrow-right-short fs-2 lh-1 ms-2"></i>
        </a>
      </p>
    </div>
  </div>
@endsection
