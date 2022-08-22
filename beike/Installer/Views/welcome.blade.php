@extends('installer::layouts.master')

@section('template_title')
    {{ trans('installer::installer_messages.welcome.templateTitle') }}
@endsection

@section('title')
    {{ trans('installer::installer_messages.welcome.title') }}
@endsection

@section('content')
  <div class="install-1">
    <h3 class="">{{ trans('installer::installer_messages.welcome.message') }}</h3>
    <div class="content-main d-flex justify-content-center align-items-center flex-column">
      <div class="welcome-img"><img src="{{ asset('/install/image/install-1.png') }}" class="img-fluid"></div>
      <h5 class="mb-5 text-muted fw-3 fw-normal guide-text text-center lh-base">欢迎使用安装引导，在后面的步骤中我们将检测您的系统环境和安装条件是否达标，请根据每一步中的提示信息操作，谢谢。</h5>
      <p class="text-center">
        <a href="{{ route('installer.requirements') }}" class="btn btn-primary d-flex align-items-center">
          {{ trans('installer::installer_messages.welcome.next') }}
          <i class="bi bi-arrow-right-short fs-2 lh-1 ms-2"></i>
        </a>
      </p>
    </div>
  </div>
@endsection
