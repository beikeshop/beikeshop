@extends('admin::layouts.master')

@section('title', __('admin/plugin.plugins_show'))

@section('content-area-class', 'w-max-1200')

@section('page-title-back', admin_route('plugins.index', http_build_query(request()->query())))

@section('head-form-btns', true)

@section('content')
  <div class="card h-min-600">
    <div class="card-body">
      @if (session('success'))
        <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
      @endif

      <div class="mb-4">
        <h4 class="mb-2">{{ $plugin->getLocaleName() }}</h4>
        <p class="mb-0 text-secondary">{!! $plugin->getLocaleDescription() !!}</p>
      </div>

      @if (!empty($pluginReadmeHtml))
        @push('header')
          <link rel="stylesheet" href="{{ asset('vendor/github-markdown/github-markdown.min.css') }}">
        @endpush
        <ul class="mb-4 plugin-tab-nav" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#plugin-setting-pane" type="button" role="tab" aria-selected="true">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M7 5C7 2.79086 8.79086 1 11 1C13.2091 1 15 2.79086 15 5H20C20.5523 5 21 5.44772 21 6V10.1707C21 10.4953 20.8424 10.7997 20.5774 10.9872C20.3123 11.1746 19.9728 11.2217 19.6668 11.1135C19.4595 11.0403 19.2355 11 19 11C17.8954 11 17 11.8954 17 13C17 14.1046 17.8954 15 19 15C19.2355 15 19.4595 14.9597 19.6668 14.8865C19.9728 14.7783 20.3123 14.8254 20.5774 15.0128C20.8424 15.2003 21 15.5047 21 15.8293V20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V6C3 5.44772 3.44772 5 4 5H7ZM11 3C9.89543 3 9 3.89543 9 5C9 5.23554 9.0403 5.45952 9.11355 5.66675C9.22172 5.97282 9.17461 6.31235 8.98718 6.57739C8.79974 6.84243 8.49532 7 8.17071 7H5V19H19V17C16.7909 17 15 15.2091 15 13C15 10.7909 16.7909 9 19 9V7H13.8293C13.5047 7 13.2003 6.84243 13.0128 6.57739C12.8254 6.31235 12.7783 5.97282 12.8865 5.66675C12.9597 5.45952 13 5.23555 13 5C13 3.89543 12.1046 3 11 3Z"></path></svg>
              <span class="ms-1 lh-text-base">{{ __('admin/common.edit') }}</span>
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#plugin-readme-pane" type="button" role="tab" aria-selected="false">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M13 21V23H11V21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H9C10.1947 3 11.2671 3.52375 12 4.35418C12.7329 3.52375 13.8053 3 15 3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H13ZM20 19V5H15C13.8954 5 13 5.89543 13 7V19H20ZM11 19V7C11 5.89543 10.1046 5 9 5H4V19H11Z"></path></svg>
              <span class="ms-1 lh-text-base">{{ __('admin/plugin.plugin_readme') }}</span>
            </button>
          </li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade show active" id="plugin-setting-pane" role="tabpanel">
      @endif

      @hookwrapper('admin.plugin.form')
        <form class="needs-validation" novalidate action="{{ admin_route('plugins.update', [$plugin->code]) }}" method="POST" id="form-app">
          @csrf
          {{ method_field('put') }}

          @foreach ($plugin->getColumns() as $column)
            @if ($column['type'] == 'image')
              <x-admin-form-image
                :name="$column['name']"
                :title="$column['label']"
                :description="$column['description'] ?? ''"
                :error="$errors->first($column['name'])"
                :required="$column['required'] ? true : false"
                :value="old($column['name'], $column['value'] ?? '')">
                <div class="help-text font-size-12 lh-base">{{ __('common.recommend_size') }} {{ $column['recommend_size'] ?? '100*100' }}</div>
              </x-admin-form-image>
            @endif

            @if ($column['type'] == 'string')
              <x-admin-form-input
                :name="$column['name']"
                :title="$column['label']"
                :placeholder="$column['placeholder'] ?? ''"
                :description="$column['description'] ?? ''"
                :error="$errors->first($column['name'])"
                :required="$column['required'] ? true : false"
                :value="old($column['name'], $column['value'] ?? '')" />
            @endif

            @if ($column['type'] == 'string-multiple')
              <x-admin-form-input-locale
                :name="$column['name'].'.*'"
                :title="$column['label']"
                :placeholder="$column['placeholder'] ?? ''"
                :error="$errors->first($column['name'])"
                :required="$column['required'] ? true : false"
                :value="old($column['name'], $column['value'] ?? '')" />
            @endif

            @if ($column['type'] == 'select')
              <x-admin-form-select
                :name="$column['name']"
                :title="$column['label']"
                :value="old($column['name'], $column['value'] ?? '')"
                :options="$column['options']">
                @if (isset($column['description']))
                  <div class="help-text font-size-12 lh-base">{{ $column['description'] }}</div>
                @endif
              </x-admin-form-select>
            @endif

            @if ($column['type'] == 'bool')
              <x-admin-form-switch
                :name="$column['name']"
                :title="$column['label']"
                :value="old($column['name'], $column['value'] ?? '')">
                @if (isset($column['description']))
                  <div class="help-text font-size-12 lh-base">{{ $column['description'] }}</div>
                @endif
              </x-admin-form-switch>
            @endif

            @if ($column['type'] == 'textarea')
              <x-admin-form-textarea
                :name="$column['name']"
                :title="$column['label']"
                :required="$column['required'] ? true : false"
                :value="old($column['name'], $column['value'] ?? '')">
                @if (isset($column['description']))
                  <div class="help-text font-size-12 lh-base">{{ $column['description'] }}</div>
                @endif
              </x-admin-form-textarea>
            @endif

            @if ($column['type'] == 'rich-text')
              <x-admin-form-rich-text
                :name="$column['name']"
                :title="$column['label']"
                :value="old($column['name'], $column['value'] ?? '')"
                :required="$column['required'] ? true : false"
                :multiple="$column['multiple'] ?? false"
                >
                @if (isset($column['description']))
                  <div class="help-text font-size-12 lh-base">{{ $column['description'] }}</div>
                @endif
              </x-admin-form-rich-text>
            @endif

            @if ($column['type'] == 'checkbox')
              <x-admin::form.row :title="$column['label']" :required="$column['required'] ? true : false">
                <div class="form-checkbox {{ $column['required'] ? 'required' : '' }}">
                  @foreach ($column['options'] as $item)
                  <div class="form-check d-inline-block mt-2 me-3">
                    <input
                      class="form-check-input"
                      name="{{ $column['name'] }}[]"
                      type="checkbox"
                      value="{{ $item['value'] }}"
                      {{ in_array($item['value'], old($column['name'], json_decode($column['value'] ?? '[]', true))) ? 'checked' : '' }}
                      id="flexCheck-{{ $column['name'] }}-{{ $loop->index }}">
                    <label class="form-check-label" for="flexCheck-{{ $column['name'] }}-{{ $loop->index }}">
                      {{ $item['label'] }}
                    </label>
                  </div>
                  @endforeach
                  <div class="invalid-feedback">{{ __('common.please_choose') }}</div>
                </div>
                @if (isset($column['description']))
                  <div class="help-text font-size-12 lh-base">{{ $column['description'] }}</div>
                @endif
              </x-admin::form.row>
            @endif

            @if ($column['type'] == 'file')
              <x-admin::form.row :title="$column['label']" :required="$column['required'] ? true : false">
                <div class="wp-400">
                  @php
                    $name = old($column['name'], $column['value'] ?? '');
                    if ($name) {
                      $name = explode('/', $name);
                      $name = end($name);
                    }
                  @endphp
                  <label class="btn border mb-1" data-toggle="tooltip">
                    <i class="bi bi-file-earmark"></i> <span>{{ __('common.select_file') }}</span>
                    <input type="file" class="d-none input-file {{ $errors->first($column['name']) ? 'is-invalid' : '' }}">
                    <input class="d-none file-value" name="{{ $column['name'] }}" value="{{ old($column['name'], $column['value'] ?? '') }}" {{ $column['required'] ? 'required' : '' }}>
                    <div class="invalid-feedback">{{ __('common.please_choose') }}</div>
                  </label>
                  <div class="file-name {{ !$name ? 'd-none' : '' }}"><a href="{{ asset(old($column['name'], $column['value'] ?? '')) }}" target="_blank">{{ $name }}</a></div>
                </div>
                @if ($errors->first($column['name']))
                  <div class="invalid-feedback d-block">{{ $errors->first($column['name']) }}</div>
                @endif
              </x-admin::form.row>
            @endif

            @if ($column['type'] == 'string_group')
              <x-admin::form.row :title="$column['label']" :required="$column['required'] ? true : false">
                <div class="input-group wp-400">
                  @if ($column['left'] ?? false)
                  <span class="input-group-text">{{ $column['left'] }}</span>
                  @endif
                  <input type="text" class="form-control {{ $errors->first($column['name']) ? 'is-invalid' : '' }}" name="{{ $column['name'] }}" value="{{ old($column['name'], $column['value'] ?? '') }}" placeholder="{{ $column['placeholder'] ?? '' }}">
                  @if ($column['right'] ?? false)
                  <span class="input-group-text">{{ $column['right'] }}</span>
                  @endif
                </div>
                @if ($errors->first($column['name']))
                  <div class="invalid-feedback d-block">{{ $errors->first($column['name']) }}</div>
                @endif

                @if (isset($column['description']))
                  <div class="help-text font-size-12 lh-base">{{ $column['description'] }}</div>
                @endif
              </x-admin::form.row>
            @endif
          @endforeach

          <x-admin::form.row title="">
            <button type="submit" class="btn btn-primary btn-lg mt-4">{{ __('common.submit') }}</button>
          </x-admin::form.row>
        </form>
      @endhookwrapper

      @if (!empty($pluginReadmeHtml))
          </div>
          <div class="tab-pane fade" id="plugin-readme-pane" role="tabpanel">
            <div class="markdown-body">{!! $pluginReadmeHtml !!}</div>
          </div>
        </div>
      @endif
    </div>
  </div>

  <img src="https://beikeshop.com/install/plugin.jpg?version={{ config('beike.version') }}&build_date={{ config('beike.build') }}&plugin={{ $plugin->code }}" class="d-none">
@endsection

@push('footer')
  <script>
    $(function () {
      $('.form-checkbox input[type="checkbox"]').on('change', function () {
        const isAllUnchecked = $(this).parents('.form-checkbox').find('input[type="checkbox"]:checked').length === 0;
        const name = $(this).attr('name');

        if (isAllUnchecked) {
          $(this).parents('.form-checkbox').append(`<input type="hidden" name="${name}" class="placeholder-input" value="">`);
        } else {
          $(this).parents('.form-checkbox').find('.placeholder-input').remove();
        }
      });

      $('.input-file').on('change', function () {
        const self = $(this);
        const file = $(this)[0].files[0];
        var formData = new FormData();

        formData.append('file', file, file.name);
        formData.append('type', 'plugin_file');
        $http.post('{{ admin_route('file.store') }}', formData).then(res => {
          if (res.status == 'success') {
            const fileName = res.data.value.split('/').pop();
            self.next('.file-value').val(res.data.value);
            self.parent().next('.file-name').removeClass('d-none').find('a').attr('href', res.data.url).text(fileName);
          }
        })

        $(this).val('');
      });
    });
  </script>
@endpush