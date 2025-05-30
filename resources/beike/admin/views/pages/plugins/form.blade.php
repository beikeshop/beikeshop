@extends('admin::layouts.master')

@section('title', __('admin/plugin.plugins_show'))

@section('content')
  <div class="card h-min-600">
    <div class="card-body">
      <h6 class="border-bottom pb-3 mb-4">{{ $plugin->getLocaleName() }}</h6>

      @if (session('success'))
        <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
      @endif

      @hookwrapper('admin.plugin.form')
        <form class="needs-validation" novalidate action="{{ admin_route('plugins.update', [$plugin->code]) }}" method="POST">
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
