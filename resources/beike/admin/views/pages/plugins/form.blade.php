@extends('admin::layouts.master')

@section('title', '插件编辑')

@section('content')
  <div class="card">
    <div class="card-body pt-5">
      <form action="{{ admin_route('plugins.update', [$plugin->code]) }}" method="POST">
        @csrf
        {{ method_field('put') }}

        @foreach ($plugin->getColumns() as $column)
          @if ($column['type'] == 'string')
            <x-admin-form-input
              :name="$column['name']"
              :title="$column['label']"
              :required="$column['required'] ? true : false"
              :value="old($column['value'], $column['value'] ?? '')">
              @if (isset($column['description']))
                <div class="help-text font-size-12 lh-base">{{ $column['description'] }}</div>
              @endif
            </x-admin-form-input>
          @endif

          @if ($column['type'] == 'select')
            <x-admin-form-select
              :name="$column['name']"
              :title="$column['label']"
              :value="old($column['value'], $column['value'] ?? '')"
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
              :value="old($column['value'], $column['value'] ?? '')">
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
              :html="isset($column['html']) ? true : false"
              :value="old($column['value'], $column['value'] ?? '')">
              @if (isset($column['description']))
                <div class="help-text font-size-12 lh-base">{{ $column['description'] }}</div>
              @endif
            </x-admin-form-textarea>
          @endif
        @endforeach

        <x-admin::form.row title="">
          <button type="submit" class="btn btn-primary btn-lg mt-4">提交</button>
        </x-admin::form.row>
      </form>
    </div>
  </div>
@endsection
