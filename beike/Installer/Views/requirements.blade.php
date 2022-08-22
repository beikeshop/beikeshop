@extends('installer::layouts.master')

@section('template_title')
  {{ trans('installer::installer_messages.requirements.templateTitle') }}
@endsection

@section('title')
  <i class="fa fa-list-ul fa-fw" aria-hidden="true"></i>
  {{ trans('installer::installer_messages.requirements.title') }}
@endsection

@section('content')
  <div class="install-2">
    <h3 class="mb-5">系统环境检测</h3>
    <table class="table table-hover text-black text-opacity-75 fs-5 mb-5">
      <thead>
        <th width="70%">环境</th>
        <th width="30%">状态</th>
      </thead>
      <tbody>
        @foreach ($requirements['requirements'] as $type => $requirement)
          <tr>
            <td colspan="3">
              <strong class="fs-4">{{ ucfirst($type) }}</strong>
              @if ($type == 'php')
              <small>(version {{ $phpSupportInfo['minimum'] }} required)</small>
              <span class="float-right">
                <strong>{{ $phpSupportInfo['current'] }}</strong>
                <i class="bi bi-{{ $phpSupportInfo['supported'] ? 'check2' : 'x' }} row-icon"
                  aria-hidden="true"></i>
              </span>
            @endif
            </td>
          </tr>
          @foreach ($requirements['requirements'][$type] as $extention => $enabled)
            <tr class="{{ $enabled ? '' : 'table-danger' }}">
              <td>{{ $extention }}</td>
              <td class="{{ $enabled ? 'text-success' : 'text-danger' }}"><i class="bi bi-{{ $enabled ? 'check-circle-fill' : 'exclamation-circle-fill' }} row-icon"
                aria-hidden="true"></i></td>
            </tr>
          @endforeach

          {{-- <ul class="list-group">
            <li class="list__item list__title {{ $phpSupportInfo['supported'] ? 'success' : 'error' }}">
              <strong>{{ ucfirst($type) }}</strong>
              @if ($type == 'php')
                <small>(version {{ $phpSupportInfo['minimum'] }} required)</small>
                <span class="float-right">
                  <strong>{{ $phpSupportInfo['current'] }}</strong>
                  <i class="bi bi-{{ $phpSupportInfo['supported'] ? 'check-circle-fill' : 'exclamation-circle-fill' }} row-icon"
                    aria-hidden="true"></i>
                </span>
              @endif
            </li>
            @foreach ($requirements['requirements'][$type] as $extention => $enabled)
              <li class="list__item {{ $enabled ? 'success' : 'error' }}">
                {{ $extention }}
                <i class="fa fa-fw fa-{{ $enabled ? 'check-circle-o' : 'exclamation-circle' }} row-icon"
                  aria-hidden="true"></i>
              </li>
            @endforeach
          </ul> --}}
        @endforeach
      </tbody>
    </table>

    @if (!isset($requirements['errors']) && $phpSupportInfo['supported'])
      <div class="d-flex justify-content-end">
        <a class="btn btn-primary d-flex align-items-center" href="{{ route('installer.permissions') }}">
          {{ trans('installer::installer_messages.requirements.next') }}
          <i class="bi bi-arrow-right-short fs-2 lh-1 ms-2"></i>
        </a>
      </div>
    @endif
  </div>
@endsection
