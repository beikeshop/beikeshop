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
            @if ($column['type'] == 'string')
              <x-admin-form-input
                :name="$column['name']"
                :title="$column['label']"
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
              :multiple="$column['multiple']"
              >
              @if (isset($column['description']))
                <div class="help-text font-size-12 lh-base">{{ $column['description'] }}</div>
              @endif
            </x-admin-form-rich-text>
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
