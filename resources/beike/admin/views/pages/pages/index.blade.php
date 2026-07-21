@extends('admin::layouts.master')

@section('title', __('admin/page.index'))

@section('content')

@if ($errors->has('error'))
<x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4" />
@endif

@if (session()->has('success'))
<x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4" />
@endif

<div class="card">
  <div class="card-body h-min-600">
    <div id="filter" class="bg-light rounded-3 p-4 mb-4">
      <div class="row">
        <div class="col-xxl-20 col-xl-3 col-lg-4 col-md-4 d-flex align-items-center mb-3">
          <label class="filter-title">{{ __('product.category') }}</label>
          <select v-model="filter.category_id" class="form-select">
            <option value="">{{ __('common.all') }}</option>
            @foreach ($categories as $_category)
              <option :value="{{ $_category['id'] }}">{{ $_category['title'] }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="row">
        <label class="filter-title"></label>
        <div class="col-auto">
          <button type="button" @click="search"
                  class="btn btn-outline-primary btn-sm">{{ __('common.filter') }}</button>
          <button type="button" @click="resetSearch"
                  class="btn btn-default btn-sm">{{ __('common.reset') }}</button>
        </div>
      </div>
    </div>
    <div class="d-flex justify-content-between mb-4">
      <a href="{{ admin_route('pages.create') }}" class="btn btn-primary">{{ __('common.add') }}</a>
    </div>
    <div class="table-push">
      @if (count($pages_format))
      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>{{ __('common.title') }}</th>
            <th>{{ __('admin/common.page_category') }}</th>
            <th>{{ __('common.status') }}</th>
            <th>{{ __('page_category.views') }}</th>
            <th>{{ __('common.created_at') }}</th>
            <th>{{ __('common.updated_at') }}</th>
            @hook('admin.page.list.column')
            <th class="text-end">{{ __('common.action') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pages_format as $page)
          <tr class="cursor-pointer row-link" data-to-url="{{ admin_route("pages.edit", [$page['id'], http_build_query(request()->query())]) }}">
            <td>{{ $page['id'] }}</td>
            <td>
              <div title="{{ $page['title'] ?? '' }}">{{ $page['title_format'] ?? ''}}</div>
            </td>
            <td>{{ $page['category_name'] ?? ''}}</td>
            <td class="{{ $page['active'] ? 'text-success' : 'text-secondary' }}">
              {{ $page['active'] ? __('common.enable') : __('common.disable') }}
            </td>
            <td>{{ $page['views'] }}</td>
            <td>{{ $page['created_at'] }}</td>
            <td>{{ $page['updated_at'] }}</td>
            @hook('admin.page.list.column_value')
            <td class="text-end" onclick="event.stopPropagation();">
              <button class="btn btn-outline-danger btn-sm delete-btn" type='button' data-id="{{ $page['id'] }}">{{ __('common.delete') }}</button>
              <a class="btn btn-default btn-sm ms-2" data-bs-toggle="tooltip" href="{{ $page['url'] }}" target="_blank" title="{{ __('admin/product.view_more') }}" class="viewPage"><i class="bi bi-eye"></i></a>
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

    {{ $pages->links('admin::vendor/pagination/bootstrap-4') }}
  </div>
</div>

@hook('admin.page.list.content.footer')
@endsection

@push('footer')
<script>
  @hook('admin.pages.list.script.before')
    let app = new Vue({
      el: '#filter',
      data: {
        url: '{{ admin_route("pages.index") }}',
        filter: {
          category_id: bk.getQueryString('category_id'),
        },
      },

      created() {
        bk.addFilterCondition(this);
      },

      methods: {
        search() {
          this.filter.page = '';
          location = bk.objectToUrlParams(this.filter, this.url)
        },

        resetSearch() {
          this.filter = bk.clearObjectValue(this.filter)
          location = bk.objectToUrlParams(this.filter, this.url)
        },
      },
    });

  $('.delete-btn').click(function(event) {
    event.stopPropagation();
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

  @hook('admin.pages.list.script.after')
</script>
@endpush