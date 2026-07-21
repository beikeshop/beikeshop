@extends('admin::layouts.master')

@section('title', __('admin/page_category.index'))

@section('content-area-class', 'w-max-1200')

@section('page-title-back', admin_route('page_categories.index', http_build_query(request()->query())))

@section('head-form-btns', true)

@section('content')
  @hook('admin.pages.form.top.before')
  @if ($errors->has('error'))
  <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4" />
  @endif

  @if (session()->has('success'))
  <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4" />
  @endif

  @hook('admin.page_category.form.top.before')

  <div id="customer-app-form">
    @hook('admin.page_category.form.content.before')
    <form novalidate class="needs-validation"
      id="form-app"
      action="{{ $page_category->id ? admin_route('page_categories.update', [$page_category->id]) : admin_route('page_categories.store') }}"
      method="POST">
      @csrf
      @method($page_category->id ? 'PUT' : 'POST')
      <div class="row">
        <div class="col-12 col-md-9">
          @hook('admin.page_category.form.main.before')

          <div class="card">
            <div class="card-body">
              <h5 class="mb-3">{{ __('admin/product.basic_information') }}</h5>

              @hook('admin.page_category.form.main.content.before')

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
                    @hook('admin.page_category.form.content.form.before')
                    <x-admin-form-input
                      error="{{ $error_title }}"
                      name="descriptions[{{ $language['code'] }}][title]"
                      title="{{ __('admin/page.info_title') }}"
                      :required="true"
                      value="{{ old('descriptions.' . $language['code'] . '.title', $descriptions[$language['code']]['title'] ?? '') }}"
                    />
                    <x-admin::form.row title="{{ __('page_category.text_summary') }}">
                      <div class="input-group">
                        <textarea rows="4" type="text" name="descriptions[{{ $language['code'] }}][summary]" class="form-control wp-400" placeholder="分类概述">{{ old('descriptions.' . $language['code'] . '.summary', $descriptions[$language['code']]['summary'] ?? '') }}</textarea>
                      </div>
                    </x-admin::form.row>

                    <input type="hidden" name="descriptions[{{ $language['code'] }}][locale]" value="{{ $language['code'] }}">
                    <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_title]" title="{{ __('admin/setting.meta_title') }}" value="{{ old('descriptions.' . $language['code'] . '.meta_title', $descriptions[$language['code']]['meta_title'] ?? '') }}" />
                    <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_description]" title="{{ __('admin/setting.meta_description') }}" value="{{ old('descriptions.' . $language['code'] . '.meta_description', $descriptions[$language['code']]['meta_description'] ?? '') }}" />
                    <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_keywords]" title="{{ __('admin/setting.meta_keywords') }}" value="{{ old('descriptions.' . $language['code'] . '.meta_keywords', $descriptions[$language['code']]['meta_keywords'] ?? '') }}" />
                    @hook('admin.page_category.form.content.form.after')
                  </div>
                @endforeach
                @hook('admin.page_category.edit.base.after')
              </div>

              @hook('admin.page_category.form.main.content.after')
            </div>
          </div>

          @hook('admin.page_category.form.main.after')
        </div>
        <div class="col-12 col-md-3">
          @hook('admin.page_category.form.sidebar.before')

          <div class="card">
            <div class="card-body">
              <h5 class="mb-3">{{ __('common.data') }}</h5>

              @hook('admin.page_category.data.before')

              <x-admin::form.row title="{{ __('admin/category.parent_category') }}">
                <div id="app">
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

          @hook('admin.page_category.form.sidebar.after')
        </div>
      </div>
      <button type="submit" class="d-none">{{ __('common.save') }}</button>
    </form>
    @hook('admin.page_category.form.content.after')
  </div>
@endsection

@push('footer')
  <script>
    var app = new Vue({
      el: '#app',

      data: {
        category_name: '{{ old('category_name', $page_category->parent->description->title ?? '') }}',
        category_id: '{{ old('categories_id', $page_category->parent->id ?? '') }}',
        @hook('admin.page_category.form.vue.data')
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

        @hook('admin.page_category.form.vue.methods')
      },

      @hook('admin.page_category.form.vue.options')
    })
  </script>
@endpush
