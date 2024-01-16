@extends('admin::layouts.master')

@section('title', __('admin/page_category.index'))

@section('page-bottom-btns')
  <button type="button" class="btn w-min-100 btn-primary submit-form btn-lg" form="form-page-categories">{{ __('common.save') }}</button>
  <button class="btn btn-lg btn-default ms-2" onclick="bk.back()">{{ __('common.return') }}</button>
@endsection

@section('content')
  @if ($errors->has('error'))
  <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4" />
  @endif

  <ul class="nav nav-tabs nav-bordered mb-3" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-content" type="button" >{{ __('admin/product.basic_information') }}</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-data" type="button">{{ __('common.data') }}</button>
    </li>
  </ul>

  <div id="customer-app-form" class="card">
    <div class="card-body h-min-600">
      <form novalidate class="needs-validation"
        id="form-page-categories"
        action="{{ $page_category->id ? admin_route('page_categories.update', [$page_category->id]) : admin_route('page_categories.store') }}"
        method="POST">
        @csrf
        @method($page_category->id ? 'PUT' : 'POST')

        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-content">
            <ul class="nav nav-tabs mb-3" role="tablist">
              @foreach (locales() as $language)
                <li class="nav-item" role="presentation">
                  <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#tab-{{ $language['code'] }}" type="button" >{{ $language['name'] }}</button>
                </li>
              @endforeach
            </ul>
            <div class="tab-content">
              @foreach (locales() as $language)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $language['code'] }}">
                  @php
                    $error_title = $errors->first("descriptions.{$language['code']}.title");
                  @endphp
                  <x-admin-form-input
                    error="{{ $error_title }}"
                    name="descriptions[{{ $language['code'] }}][title]"
                    title="{{ __('admin/page.info_title') }}"
                    :required="true"
                    value="{{ old('descriptions.' . $language['code'] . '.title', $descriptions[$language['code']]['title'] ?? '') }}"
                  />
                  <x-admin::form.row title="{{ __('page_category.text_summary') }}">
                    <div class="input-group w-max-400">
                      <textarea rows="4" type="text" name="descriptions[{{ $language['code'] }}][summary]" class="form-control wp-400" placeholder="分类概述">{{ old('descriptions.' . $language['code'] . '.summary', $descriptions[$language['code']]['summary'] ?? '') }}</textarea>
                    </div>
                  </x-admin::form.row>

                  <input type="hidden" name="descriptions[{{ $language['code'] }}][locale]" value="{{ $language['code'] }}">
                  <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_title]" title="{{ __('admin/setting.meta_title') }}" value="{{ old('descriptions.' . $language['code'] . '.meta_title', $descriptions[$language['code']]['meta_title'] ?? '') }}" />
                  <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_description]" title="{{ __('admin/setting.meta_description') }}" value="{{ old('descriptions.' . $language['code'] . '.meta_description', $descriptions[$language['code']]['meta_description'] ?? '') }}" />
                  <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_keywords]" title="{{ __('admin/setting.meta_keywords') }}" value="{{ old('descriptions.' . $language['code'] . '.meta_keywords', $descriptions[$language['code']]['meta_keywords'] ?? '') }}" />
                </div>
              @endforeach
              @hook('admin.page_category.edit.base.after')
            </div>
          </div>
          <div class="tab-pane fade" id="tab-data">
            @hook('admin.page_category.data.before')

            <x-admin::form.row title="{{ __('admin/category.parent_category') }}">
              <div class="wp-400" id="app">
                <el-autocomplete
                v-model="category_name"
                value-key="name"
                size="small"
                name="category_name"
                class="w-100"
                :fetch-suggestions="relationsQuerySearch"
                placeholder="{{ __('common.input') }}"
                @select="handleSelect"
                ></el-autocomplete>
                <input type="hidden" name="parent_id" :value="category_name ? category_id : ''" />
              </div>
            </x-admin::form.row>
            <x-admin-form-input name="position" title="{{ __('common.sort_order') }}" value="{{ old('position', $page_category->position ?? 0) }}" />

            @hook('admin.page_category.data.after')

            <x-admin-form-switch name="active" title="{{ __('common.status') }}" value="{{ old('active', $page_category->active ?? 1) }}" />
          </div>
        </div>

        <button type="submit" class="d-none">{{ __('common.save') }}</button>
      </form>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    var app = new Vue({
      el: '#app',

      data: {
        category_name: '{{ old('category_name', $page_category->parent->description->title ?? '') }}',
        category_id: '{{ old('categories_id', $page_category->parent->id ?? '') }}',
      },

      methods: {
        relationsQuerySearch(keyword, cb) {
          $http.get('page_categories/autocomplete?name=' + encodeURIComponent(keyword), null, {hload:true}).then((res) => {
            cb(res.data);
          })
        },

        handleSelect(item) {
          this.category_name = item.name
          this.category_id = item.id
        },
      }
    })
  </script>
@endpush
