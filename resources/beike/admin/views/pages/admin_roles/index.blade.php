@extends('admin::layouts.master')

@section('title', __('admin/common.admin_user'))

@section('content')
  <ul class="nav-bordered nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link" href="{{ admin_route('admin_users.index') }}">{{ __('admin/common.admin_user') }}</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="{{ admin_route('admin_roles.index') }}">{{ __('admin/common.admin_role') }}</a>
    </li>
  </ul>

  <div id="tax-classes-app" class="card">
    <div class="card-body h-min-600">
      <div class="d-flex justify-content-between mb-4">
        <a href="{{ admin_route('admin_roles.create') }}"
          class="btn btn-primary">{{ __('admin/role.admin_roles_create') }}</a>
      </div>
      <div class="table-push">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>{{ __('common.name') }}</th>
              <th>{{ __('common.created_at') }}</th>
              <th>{{ __('common.updated_at') }}</th>
              <th class="text-end">{{ __('common.action') }}</th>
            </tr>
          </thead>
          <tbody>
            @if (count($roles))
              @foreach ($roles as $role)
                <tr>
                  <td>{{ $role->id }}</td>
                  <td>{{ $role->name }}</td>
                  <td>{{ $role->created_at }}</td>
                  <td>{{ $role->updated_at }}</td>
                  <td class="text-end">
                    <a href="{{ admin_route('admin_roles.edit', [$role->id]) }}"
                      class="btn btn-outline-secondary btn-sm">{{ __('common.edit') }}</a>
                    <button class="btn btn-outline-danger btn-sm ml-1 delete-role" data-id="{{ $role->id }}"
                      type="button">{{ __('common.delete') }}</button>
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="5" class="border-0"><x-admin-no-data /></td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    $('.delete-role').click(function(event) {
      const id = $(this).data('id');
      const self = $(this);

      layer.confirm('{{ __('common.confirm_delete') }}', {
        title: "{{ __('common.text_hint') }}",
        btn: ['{{ __('common.cancel') }}', '{{ __('common.confirm') }}'],
        area: ['400px'],
        btn2: () => {
          $http.delete(`admin_roles/${id}`).then((res) => {
            layer.msg(res.message);
            self.parents('tr').remove()
          })
        }
      })
    });
  </script>
@endpush
