@extends('admin::layouts.master')

@section('title', __('admin/common.admin_role'))

@section('page-title-back', true)

@section('page-title-right')
<a class="btn btn-primary" href="{{ admin_route('admin_users.index') }}"><i class="bi bi-box-arrow-up-right"></i> {{ __('admin/common.admin_user') }}</a>
@endsection

@section('content')
  <div id="tax-classes-app" class="card">
    <div class="card-body h-min-600">
      @hook('admin.admin_roles.index.content.before')
      <div class="d-flex justify-content-between mb-4">
        <a href="{{ admin_route('admin_roles.create') }}"
          class="btn btn-primary">{{ __('admin/role.admin_roles_create') }}</a>
      </div>
      @if (count($roles))
        <div class="table-push">
          @hook('admin.admin_roles.index.before')
          <table class="table table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>{{ __('common.name') }}</th>
                <th>{{ __('common.created_at') }}</th>
                <th>{{ __('common.updated_at') }}</th>
                @hook('admin.admin_roles.index.thead')
                <th class="text-end">{{ __('common.action') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($roles as $role)
                <tr class="cursor-pointer row-link" data-to-url="{{ admin_route('admin_roles.edit', [$role->id]) }}">
                  <td>{{ $role->id }}</td>
                  <td>{{ $role->name }}</td>
                  <td>{{ $role->created_at }}</td>
                  <td>{{ $role->updated_at }}</td>
                  @hook('admin.admin_roles.index.tbody', $role)
                  <td class="text-end">
                    @hook('admin.admin_roles.index.tbody.actions.before', $role)
                    <button class="btn btn-outline-danger btn-sm ml-1 delete-role" data-id="{{ $role->id }}"
                      type="button">{{ __('common.delete') }}</button>
                    @hook('admin.admin_roles.index.tbody.actions.after', $role)
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          @hook('admin.admin_roles.index.after')
        </div>
      @else
        <div>
          <x-admin-no-data />
        </div>
      @endif
      @hook('admin.admin_roles.index.content.after')
    </div>
  </div>
@endsection

@push('footer')
  <script>
    $('.delete-role').click(function(event) {
      event.stopPropagation();
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
