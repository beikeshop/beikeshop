@extends('installer::layouts.master')

@section('template_title')
  {{ trans('installer::installer_messages.permissions.templateTitle') }}
@endsection

@section('title')
  <i class="fa fa-key fa-fw" aria-hidden="true"></i>
  {{ trans('installer::installer_messages.permissions.title') }}
@endsection

@section('content')
  <div class="install-2">
    <h3 class="mb-5">文件夹权限检测</h3>
    {{-- <ul class="list">
      @foreach ($permissions['permissions'] as $permission)
        <li class="list__item list__item--permissions {{ $permission['isSet'] ? 'success' : 'error' }}">
          {{ $permission['folder'] }}
          <span>
            <i class="fa fa-fw fa-{{ $permission['isSet'] ? 'check-circle-o' : 'exclamation-circle' }}"></i>
            {{ $permission['permission'] }}
          </span>
        </li>
      @endforeach
    </ul> --}}

    <table class="table table-hover text-black text-opacity-75 fs-5 mb-5">
      <thead>
        <tr>
          <th width="70%">文件夹</th>
          <th width="30%">状态</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($permissions['permissions'] as $permission)
          <tr class="{{ $permission['isSet'] ? '' : 'table-danger' }}">
            <td>{{ $permission['folder'] }} {{ $permission['permission'] }}</td>
            <td>
              <i class="bi bi-{{ $permission['isSet'] ? 'check-circle-fill' : 'exclamation-circle-fill' }} {{ $permission['isSet'] ? 'text-success' : 'text-danger' }} row-icon"
                aria-hidden="true"></i>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  @if (!isset($permissions['errors']))
    <div class="d-flex justify-content-end">
      <a class="btn btn-primary d-flex align-items-center" href="{{ route('installer.environment') }}">
        {{ trans('installer::installer_messages.permissions.next') }}
        <i class="bi bi-arrow-right-short fs-2 lh-1 ms-2"></i>
      </a>
    </div>
  @endif
@endsection
