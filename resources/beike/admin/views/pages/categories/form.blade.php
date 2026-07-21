@extends('admin::layouts.master')

@section('title', __('admin/common.category'))

@section('content-area-class', 'w-max-1200')

@section('page-title-back', admin_route('categories.index', http_build_query(request()->query())))

@section('head-form-btns', true)

@section('content')
<div id="category-app">
  @hook('admin.categories.form.before')

  @if (session('success'))
  <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4" />
  @endif

  <form class="needs-validation" id="form-app" novalidate
    action="{{ admin_route($category->id ? 'categories.update' : 'categories.store', $category) }}" method="POST">
    @csrf
    @method($category->id ? 'PUT' : 'POST')
    <input type="hidden" name="_redirect" value="{{ $_redirect }}">
    <div class="row g-3">
      <div class="col-lg-9">
        @hook('admin.categories.form.main.before')

        <div class="card">
          <div class="card-body">
            <h5 class="mb-3">{{ __('admin/category.edit_category') }}</h5>
            @hook('admin.category.form.before')

            <x-admin-form-input-locale name="descriptions.*.name" title="{{ __('common.name') }}" :value="$descriptions"
              :required="true" />
            @hook('admin.product.categories.edit.name.after')

            <x-admin::form.row title="{{ __('admin/builder.modules_content') }}">
              @foreach ($languages as $language)
              <div class="input-group">
                <span class="input-group-text">{{ $language['name'] }}</span>
                <textarea rows="2" type="text" name="descriptions[{{ $language['code'] }}][content]"
                  class="{{ $errors->first(" descriptions.{$language['code']}.content") ? 'is-invalid' : '' }}
                  form-control input-{{ $language['code'] }}"
                  placeholder="{{ __('admin/builder.modules_content') }}">{{ old('descriptions.' . $language['code'] . '.content', $category->descriptions->keyBy('locale')[$language->code]->content ?? '') }}</textarea>
                <div class="invalid-feedback">{{ $errors->first("descriptions.{$language['code']}.content") }}</div>
              </div>
              @endforeach
              @include('admin::shared.auto-translation')
            </x-admin::form.row>
            @hook('admin.product.categories.edit.content.after')

            <x-admin-form-input name="position" title="{{ __('common.sort_order') }}"
              :value="old('position', $category->position ?? 0)" />

            @hook('admin.categories.form.name.after')

            <x-admin::form.row title="Meta title">
              @foreach ($languages as $language)
              <div class="input-group">
                <span class="input-group-text">{{ $language['name'] }}</span>
                <textarea rows="2" type="text" name="descriptions[{{ $language['code'] }}][meta_title]"
                  class="{{ $errors->first(" descriptions.{$language['code']}.meta_title") ? 'is-invalid' : '' }}
                  form-control input-{{ $language['code'] }} wp-400"
                  placeholder="Meta title">{{ old('descriptions.' . $language['code'] . '.meta_title', $category->descriptions->keyBy('locale')[$language->code]->meta_title ?? '') }}</textarea>
                <div class="invalid-feedback">{{ $errors->first("descriptions.{$language['code']}.meta_title") }}</div>
              </div>
              @endforeach
              @include('admin::shared.auto-translation')
            </x-admin::form.row>
            @hook('admin.product.categories.edit.meta_title.after')
            <x-admin::form.row title="Meta keywords">
              @foreach ($languages as $language)
              <div class="input-group">
                <span class="input-group-text">{{ $language['name'] }}</span>
                <textarea rows="2" type="text" name="descriptions[{{ $language['code'] }}][meta_keywords]"
                  class="{{ $errors->first(" descriptions.{$language['code']}.meta_keywords") ? 'is-invalid' : '' }}
                  form-control input-{{ $language['code'] }} wp-400"
                  placeholder="Meta keywords">{{ old('descriptions.' . $language['code'] . '.meta_keywords', $category->descriptions->keyBy('locale')[$language->code]->meta_keywords ?? '') }}</textarea>
                <div class="invalid-feedback">{{ $errors->first("descriptions.{$language['code']}.meta_keywords") }}
                </div>
              </div>
              @endforeach
              @include('admin::shared.auto-translation')
            </x-admin::form.row>
            @hook('admin.product.categories.edit.meta_keywords.after')
            <x-admin::form.row title="Meta description">
              @foreach ($languages as $language)
              <div class="input-group">
                <span class="input-group-text">{{ $language['name'] }}</span>
                <textarea rows="2" type="text" name="descriptions[{{ $language['code'] }}][meta_description]"
                  class="{{ $errors->first(" descriptions.{$language['code']}.meta_description") ? 'is-invalid' : '' }}
                  form-control input-{{ $language['code'] }} wp-400"
                  placeholder="Meta description">{{ old('descriptions.' . $language['code'] . '.meta_description', $category->descriptions->keyBy('locale')[$language->code]->meta_description ?? '') }}</textarea>
                <div class="invalid-feedback">{{ $errors->first("descriptions.{$language['code']}.meta_description") }}
                </div>
              </div>
              @endforeach
              @include('admin::shared.auto-translation')
            </x-admin::form.row>
            @hook('admin.product.categories.edit.meta_description.after')

            @hook('admin.category.form.after')
          </div>
        </div>

        @hook('admin.categories.form.main.after')
      </div>
      <div class="col-lg-3">
        @hook('admin.categories.form.sidebar.before')

        <div class="card mb-3">
          <div class="card-body pb-0">
            <h5 class="mb-3">{{ __('common.status') }}</h5>
            <x-admin-form-switch title="" name="active"
              :value="old('active', $category->active ?? 1)" />
            @hook('admin.categories.form.switch.after')
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-body pb-0">
            <h5 class="mb-3">{{ __('admin/category.parent_category') }}</h5>

            @hook('admin.categories.form.sidebar.card.parent_category.before')

            <x-admin::form.row title="">
              <select name="parent_id" id="" class="form-control short">
                <option value="0">--{{ __('common.please_choose') }}--</option>
                @foreach ($categories as $_category)
                  <option value="{{ $_category->id }}" {{ old('parent_id', $category->parent_id) == $_category->id ? 'selected' : '' }}>{{ $_category->name }}</option>
                @endforeach
              </select>
            </x-admin::form.row>

            @hook('admin.categories.form.parent.after')
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-body pb-0">
            <h5 class="mb-3">{{ __('admin/category.category_image') }}</h5>

            @hook('admin.categories.form.sidebar.card.category_image.before')

            <x-admin-form-image :is-remove="true" name="image" title=""
              :value="old('image', $category->image ?? '')">
              <div class="help-text font-size-12 lh-base">{{ __('common.recommend_size') }} 300*300</div>
            </x-admin-form-image>

            @hook('admin.categories.form.image.after')
          </div>
        </div>

        @hook('admin.categories.form.sidebar.after')
      </div>
    </div>

    <x-admin::form.row>
      <button type="submit" class="btn btn-primary w-min-100 btn-lg mt-3">{{ __('common.save') }}</button>
      @hook('admin.categories.form.submit.after')
    </x-admin::form.row>
  </form>
  @hook('admin.categories.form.after')
</div>
@endsection