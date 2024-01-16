@extends('admin::layouts.master')

@section('title', __('admin/page_category.index'))

@section('content')
@if ($errors->has('error'))
<x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4" />
@endif

<div class="card" id="app">
  <div class="card-body h-min-600">
    <div class="d-flex justify-content-between mb-4">
      <a href="{{ admin_route('page_categories.create') }}" class="btn btn-primary">{{ __('common.add') }}</a>
    </div>
    <div class="table-push">
      @if (count($page_categories_format))
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>{{ __('common.title') }}</th>
            <th>{{ __('common.status') }}</th>
            <th>{{ __('common.created_at') }}</th>
            <th>{{ __('common.updated_at') }}</th>
            @hook('admin.page.list.column')
            <th class="text-end">{{ __('common.action') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($page_categories_format as $item)
          <tr>
            <td>{{ $item['id'] }}</td>
            <td>
              <div title="{{ $item['title'] ?? '' }}"><a class="text-dark"
                  href="{{ shop_route('page_categories.show', $item['id']) }}" target="_blank">{{ $item['title_format'] ?? ''
                  }}</a></div>
            </td>
            <td class="{{ $item['active'] ? 'text-success' : 'text-secondary' }}">
              {{ $item['active'] ? __('common.enable') : __('common.disable') }}
            </td>
            <td>{{ $item['created_at'] }}</td>
            <td>{{ $item['updated_at'] }}</td>
            @hook('admin.page.list.column_value')
            <td class="text-end">
              <a href="{{ admin_route('page_categories.edit',$item['id']) }}"
                class="btn btn-outline-secondary btn-sm">{{ __('common.edit') }}</a>
              <button class="btn btn-outline-danger btn-sm delete-btn" type='button' data-id="{{ $item['id'] }}">{{
                __('common.delete') }}</button>
              @hook('admin.page.list.action')
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
        <div><x-admin-no-data /></div>
      @endif
    </div>

    {{ $page_categories->links('admin::vendor/pagination/bootstrap-4') }}
  </div>
</div>

@hook('admin.page.list.content.footer')
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
        $http.delete(`page_categories/${id}`).then((res) => {
          layer.msg(res.message);
          window.location.reload();
        })
      }
    })
  });
</script>
@endpush