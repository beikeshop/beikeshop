@extends('admin::layouts.master')

@section('title', __('admin/page.index'))

@section('content')

  @if ($errors->has('error'))
    <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4" />
  @endif

  <div class="card">
    <div class="card-body h-min-600">
      <div class="d-flex justify-content-between mb-4">
        <a href="{{ admin_route('pages.create') }}" class="btn btn-primary">{{ __('common.add') }}</a>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>{{ __('common.title') }}</th>
            <th>{{ __('common.status') }}</th>
            <th>{{ __('common.created_at') }}</th>
            <th>{{ __('common.updated_at') }}</th>
            <th class="text-end">{{ __('common.action') }}</th>
          </tr>
        </thead>
        <tbody>
          @if (count($pages_format))
            @foreach ($pages_format as $page)
              <tr>
                <td>{{ $page['id'] }}</td>
                <td>
                  <div title="{{ $page['title'] ?? '' }}">{{ $page['title_format'] ?? '' }}</div>
                </td>
                <td class="{{ $page['active'] ? 'text-success' : 'text-secondary' }}">
                  {{ $page['active'] ? __('common.enable') : __('common.disable') }}
                </td>
                <td>{{ $page['created_at'] }}</td>
                <td>{{ $page['updated_at'] }}</td>
                <td class="text-end">
                  <a href="{{ admin_route('pages.edit', [$page['id']]) }}"
                    class="btn btn-outline-secondary btn-sm">{{ __('common.edit') }}</a>

                  <button class="btn btn-outline-danger btn-sm delete-btn" type='button'
                    data-id="{{ $page['id'] }}">{{ __('common.delete') }}</button>
                </td>
              </tr>
            @endforeach
          @else
          <tr><td colspan="5" class="border-0"><x-admin-no-data /></td></tr>
          @endif
        </tbody>
      </table>

      {{ $pages->links('admin::vendor/pagination/bootstrap-4') }}

    </div>
  </div>
@endsection

@push('footer')
  <script>
    $('.delete-btn').click(function(event) {
      const id = $(this).data('id');
      const self = $(this);

      layer.confirm('{{ __('common.confirm_delete') }}', {
        title: "{{ __('common.text_hint') }}",
        btn: ['{{ __('common.cancel') }}', '{{ __('common.confirm') }}'],
        area: ['400px'],
        btn2: () => {
          $http.delete(`pages/${id}`).then((res) => {
            layer.msg(res.message);
            window.location.reload();
          })
        }
      })
    });
  </script>
@endpush
