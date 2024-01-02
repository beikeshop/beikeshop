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

        @hook('admin.category.form.before')

        <x-admin-form-input-locale name="descriptions.*.name" title="{{ __('common.name') }}" :value="$descriptions" :required="true" />
        @hook('admin.product.categories.edit.name.after')
        <x-admin-form-input-locale name="descriptions.*.content" title="{{ __('admin/builder.modules_content') }}" :value="$descriptions" />
        @hook('admin.product.categories.edit.content.after')
        <x-admin-form-input name="position" title="{{ __('common.sort_order') }}" :value="old('position', $category->position ?? 0)" />

        <x-admin-form-image name="image" title="{{ __('admin/category.category_image') }}" :value="old('image', $category->image ?? '')">
          <div class="help-text font-size-12 lh-base">{{ __('common.recommend_size') }} 300*300</div>
        </x-admin-form-image>

        <x-admin::form.row title="{{ __('admin/category.parent_category') }}">
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

        <x-admin::form.row title="Meta title">
          @foreach ($languages as $language)
          <div class="input-group w-max-600">
            <span class="input-group-text wp-100">{{ $language['name'] }}</span>
            <textarea rows="2" type="text" name="descriptions[{{ $language['code'] }}][meta_title]"
              class="{{ $errors->first("descriptions.{$language['code']}.meta_title") ? 'is-invalid' : '' }} form-control input-{{ $language['code'] }} wp-400" placeholder="Meta title">{{ old('descriptions.' . $language['code'] . '.meta_title', $category->descriptions->keyBy('locale')[$language->code]->meta_title ?? '') }}</textarea>
            <div class="invalid-feedback">{{ $errors->first("descriptions.{$language['code']}.meta_title") }}</div>
          </div>
          @endforeach
          @include('admin::shared.auto-translation')
        </x-admin::form.row>
        @hook('admin.product.categories.edit.meta_title.after')
        <x-admin::form.row title="Meta keywords">
          @foreach ($languages as $language)
          <div class="input-group w-max-600">
            <span class="input-group-text wp-100">{{ $language['name'] }}</span>
            <textarea rows="2" type="text" name="descriptions[{{ $language['code'] }}][meta_keywords]"
              class="{{ $errors->first("descriptions.{$language['code']}.meta_keywords") ? 'is-invalid' : '' }} form-control input-{{ $language['code'] }} wp-400" placeholder="Meta keywords">{{ old('descriptions.' . $language['code'] . '.meta_keywords', $category->descriptions->keyBy('locale')[$language->code]->meta_keywords ?? '') }}</textarea>
            <div class="invalid-feedback">{{ $errors->first("descriptions.{$language['code']}.meta_keywords") }}</div>
          </div>
          @endforeach
          @include('admin::shared.auto-translation')
        </x-admin::form.row>
        @hook('admin.product.categories.edit.meta_keywords.after')
        <x-admin::form.row title="Meta description">
          @foreach ($languages as $language)
          <div class="input-group w-max-600">
            <span class="input-group-text wp-100">{{ $language['name'] }}</span>
            <textarea rows="2" type="text" name="descriptions[{{ $language['code'] }}][meta_description]"
              class="{{ $errors->first("descriptions.{$language['code']}.meta_description") ? 'is-invalid' : '' }} form-control input-{{ $language['code'] }} wp-400" placeholder="Meta description">{{ old('descriptions.' . $language['code'] . '.meta_description', $category->descriptions->keyBy('locale')[$language->code]->meta_description ?? '') }}</textarea>
            <div class="invalid-feedback">{{ $errors->first("descriptions.{$language['code']}.meta_description") }}</div>
          </div>
          @endforeach
          @include('admin::shared.auto-translation')
        </x-admin::form.row>
        @hook('admin.product.categories.edit.meta_description.after')

        @hook('admin.category.form.after')

        <x-admin-form-switch title="{{ __('common.status') }}" name="active" :value="old('active', $category->active ?? 1)" />

        <x-admin::form.row>
          <button type="submit" class="btn btn-primary w-min-100 btn-lg mt-3">{{ __('common.save') }}</button>
        </x-admin::form.row>
      </form>

    </div>
  </div>
@endsection
