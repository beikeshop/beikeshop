@extends('admin::layouts.master')

@section('title', __('admin/page.index'))

@section('body-class', 'page-pages-form')

@push('header')
  <script src="{{ asset('vendor/tinymce/5.9.1/tinymce.min.js') }}"></script>
@endpush

@section('page-bottom-btns')
  <button type="button" class="w-min-100 btn btn-primary submit-form btn-lg" form="form-page">{{ __('common.save') }}</button>
  <button class="btn btn-lg btn-default w-min-100 ms-3" onclick="bk.back()">{{ __('common.return') }}</button>
@endsection

@section('content')
  <ul class="nav nav-tabs nav-bordered mb-3" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-content" type="button" >{{ __('admin/product.basic_information') }}</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-data" type="button">{{ __('common.data') }}</button>
    </li>
  </ul>

  <div id="app" class="card h-min-600">
    <div class="card-body">
      <form novalidate id="form-page" class="needs-validation" action="{{ $page->id ? admin_route('pages.update', [$page->id]) : admin_route('pages.store') }}" method="POST">
        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-content">
            @csrf
            @method($page->id ? 'PUT' : 'POST')
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
                      <textarea rows="3" type="text" name="descriptions[{{ $language['code'] }}][summary]" class="form-control wp-400 {{ $errors->has("descriptions.{$language['code']}.summary") ? 'is-invalid' : '' }}" placeholder="{{ __('page_category.text_summary') }}">{{ old('descriptions.' . $language['code'] . '.summary', $descriptions[$language['code']]['summary'] ?? '') }}</textarea>
                    </div>
                    @if ($errors->has("descriptions.{$language['code']}.summary"))
                      <span class="invalid-feedback d-block" role="alert">{{ $errors->first("descriptions.{$language['code']}.summary") }}</span>
                    @endif
                  </x-admin::form.row>

                  <x-admin::form.row title="{{ __('admin/page.info_content') }}" required>
                    <div class="w-max-1000">
                      <textarea name="descriptions[{{ $language['code'] }}][content]" data-tinymce-height="600" class="form-control tinymce">
                        {{ old('descriptions.' . $language['code'] . '.content', $descriptions[$language['code']]['content'] ?? '') }}
                      </textarea>
                    </div>
                    @if ($errors->has("descriptions.{$language['code']}.content"))
                      <span class="invalid-feedback d-block" role="alert">{{ $errors->first("descriptions.{$language['code']}.content") }}</span>
                    @endif
                  </x-admin::form.row>

                  <input type="hidden" name="descriptions[{{ $language['code'] }}][locale]" value="{{ $language['code'] }}">
                  <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_title]" title="{{ __('admin/setting.meta_title') }}" value="{{ old('descriptions.' . $language['code'] . '.meta_title', $descriptions[$language['code']]['meta_title'] ?? '') }}" />
                  <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_description]" title="{{ __('admin/setting.meta_description') }}" value="{{ old('descriptions.' . $language['code'] . '.meta_description', $descriptions[$language['code']]['meta_description'] ?? '') }}" />
                  <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_keywords]" title="{{ __('admin/setting.meta_keywords') }}" value="{{ old('descriptions.' . $language['code'] . '.meta_keywords', $descriptions[$language['code']]['meta_keywords'] ?? '') }}" />
                </div>
              @endforeach
              @hook('admin.page.edit.base.after')
            </div>
          </div>
          <div class="tab-pane fade" id="tab-data">
            @hook('admin.page.data.before')

            <x-admin-form-input name="author" title="{{ __('page_category.author') }}" value="{{ old('author', $page->author ?? '') }}" />
            <x-admin::form.row title="{{ __('admin/page_category.index') }}">
              <div class="wp-400">
                <el-autocomplete
                v-model="page_category_name"
                value-key="name"
                size="small"
                name="category_name"
                class="w-100"
                :fetch-suggestions="(keyword, cb) => {relationsQuerySearch(keyword, cb, 'page_categories')}"
                placeholder="{{ __('common.input') }}"
                @select="(e) => {handleSelect(e, 'page_categories')}"
                ></el-autocomplete>
                <input type="hidden" name="page_category_id" :value="page_category_name ? page_category_id : ''" />
              </div>
            </x-admin::form.row>

            <x-admin-form-image name="image" title="{{ __('admin/page.cover_picture') }}" value="{{ old('image', $page->image ?? '') }}">
              <div class="help-text font-size-12 lh-base">{{ __('common.recommend_size') }}: 500*350</div>
            </x-admin-form-image>

            <x-admin-form-input name="views" title="{{ __('page_category.views') }}" value="{{ old('views', $page->views ?? '') }}" />

            <x-admin::form.row title="{{ __('admin/product.product_relations') }}">
              <div class="module-edit-group wp-600">
                <div class="autocomplete-group-wrapper">
                  <el-autocomplete
                    class="inline-input"
                    v-model="relations.keyword"
                    value-key="name"
                    size="small"
                    :fetch-suggestions="(keyword, cb) => {relationsQuerySearch(keyword, cb, 'products')}"
                    placeholder="{{ __('admin/builder.modules_keywords_search') }}"
                    @select="(e) => {handleSelect(e, 'product_relations')}"
                  ></el-autocomplete>

                  <div class="item-group-wrapper" v-loading="relations.loading">
                    <template v-if="relations.products.length">
                      <draggable
                        ghost-class="dragabble-ghost"
                        :list="relations.products"
                        :options="{animation: 330}"
                      >
                        <div v-for="(item, index) in relations.products" :key="index" class="item">
                          <div>
                            <i class="el-icon-s-unfold"></i>
                            <span>@{{ item.name }}</span>
                          </div>
                          <i class="el-icon-delete right" @click="relationsRemoveProduct(index)"></i>
                          <input type="text" :name="'products['+ index +']'" v-model="item.id" class="form-control d-none">
                        </div>
                      </draggable>
                    </template>
                    <template v-else>{{ __('admin/builder.modules_please_products') }}</template>
                  </div>
                </div>
              </div>
            </x-admin::form.row>

            @hook('admin.page.data.after')

            <x-admin-form-switch name="active" title="{{ __('common.status') }}" value="{{ old('active', $page->active ?? 1) }}" />
          </div>
        </div>

        <button type="submit" class="d-none">{{ __('common.save') }}</button>
      </form>
    </div>
  </div>

  @hook('admin.page.form.footer')
@endsection

@push('footer')
<script>
  var app = new Vue({
    el: '#app',

    data: {
      relations: {
        keyword: '',
        products: [],
        loading: null,
      },
      page_category_name: '{{ old('category_name', $page->category->description->title ?? '') }}',
      page_category_id: '{{ old('categories_id', $page->category->id ?? '') }}',
    },

    created() {
      const products = @json($page['products'] ?? []);

      if (products.length) {
        this.relations.products = products.map(v => {
          return {
            id: v.id,
            name: v.description.name,
          }
        })
      }
    },

    methods: {
      relationsQuerySearch(keyword, cb, url) {
        $http.get(url + '/autocomplete?name=' + encodeURIComponent(keyword), null, {hload:true}).then((res) => {
          cb(res.data);
        })
      },
      handleSelect(item, key) {
        if (key == 'product_relations') {
          if (!this.relations.products.find(v => v == item.id)) {
            this.relations.products.push(item);
          }
          this.relations.keyword = ""
        } else {
          this.page_category_name = item.name
          this.page_category_id = item.id
        }
      },
      relationsRemoveProduct(index) {
        this.relations.products.splice(index, 1);
      },
    }
  })
</script>
@endpush



