@extends('admin::layouts.master')

@section('title', __('admin/common.category'))

@section('content')
  <div id="category-app" class="card">
    <div class="card-header">{{ __('admin/category.edit_category') }}</div>
    <div class="card-body">
      <form class="needs-validation" novalidate action="{{ admin_route($category->id ? 'categories.update' : 'categories.store', $category) }}"
        method="POST">
        @csrf
        @method($category->id ? 'PUT' : 'POST')
        <input type="hidden" name="_redirect" value="{{ $_redirect }}">

        @if (session('success'))
          <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
        @endif

        <x-admin-form-input-locale name="descriptions.*.name" title="{{ __('common.name') }}" :value="$descriptions" :required="true" />
        <x-admin-form-input-locale name="descriptions.*.content" title="{{ __('admin/builder.modules_content') }}" :value="$descriptions" />

        {{-- <x-admin-form-select title="上级分类" name="parent_id" :value="old('parent_id', $category->parent_id ?? 0)" :options="$categories->toArray()" key="id" label="name" /> --}}

        <x-admin::form.row title="{{ __('admin/category.upper_category') }}">
          @php
            $_parent_id = old('parent_id', $category->parent_id ?? 0);
          @endphp
          <select name="parent_id" id="" class="form-control short wp-400">
            <option value="0">--{{ __('common.please_choose') }}--</option>
            @foreach ($categories as $_category)
              <option value="{{ $_category->id }}" {{ $_parent_id == $_category->id ? 'selected' : '' }}>
                {{ $_category->name }}
              </option>
            @endforeach
          </select>
        </x-admin::form.row>

        <x-admin-form-switch title="{{ __('common.status') }}" name="active" :value="old('active', $category->active ?? 1)" />

        <x-admin::form.row>
          <button type="submit" class="btn btn-primary mt-3">{{ __('common.save') }}</button>
        </x-admin::form.row>
      </form>

    </div>
  </div>
@endsection