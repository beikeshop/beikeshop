@extends('admin::layouts.master')

@section('title', __('admin/page.index'))

@section('body-class', 'page-pages-form')

@section('page-title-back', admin_route('pages.index', http_build_query(request()->query())))

@push('header')
  <script src="{{ asset('vendor/vue/Sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/vue/vuedraggable.js') }}"></script>
  <script src="{{ asset('vendor/tinymce/5.9.1/tinymce.min.js') }}"></script>
@endpush

@section('content-area-class', 'w-max-1200')

@section('head-form-btns', true)

@section('content')
  @hook('admin.pages.form.top.before')
  @if ($errors->has('error'))
  <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4" />
  @endif

  @if (session()->has('success'))
  <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4" />
  @endif

  <div id="app">
    @hook('admin.pages.form.content.before')

    @if ($errors->has('error'))
      <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4"/>
    @endif
    <form novalidate id="form-app" class="needs-validation" action="{{ $page->id ? admin_route('pages.update', [$page->id]) : admin_route('pages.store') }}" method="POST">
      @csrf
      @method($page->id ? 'PUT' : 'POST')

      <div class="row">
        <div class="col-12 col-md-9">
          @hook('admin.pages.form.main.before')

          <div class="card">
            <div class="card-body">
              <h5 class="mb-3">{{ __('admin/product.basic_information') }}</h5>

              @hook('admin.pages.form.main.content.before')

              <ul class="nav nav-tabs mb-3" role="tablist">
                @foreach (locales() as $language)
                  <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab"
                            data-bs-target="#tab-{{ $language['code'] }}" type="button">{{ $language['name'] }}</button>
                  </li>
                @endforeach
              </ul>
              <div class="tab-content">
                @foreach (locales() as $language)
                  <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $language['code'] }}">
                    @php
                      $error_title = $errors->first("descriptions.{$language['code']}.title");
                    @endphp
                    @hook('admin.pages.form.content.form.before')
                    <x-admin-form-input
                      error="{{ $error_title }}"
                      name="descriptions[{{ $language['code'] }}][title]"
                      title="{{ __('admin/page.info_title') }}"
                      :required="true"
                      value="{{ old('descriptions.' . $language['code'] . '.title', $descriptions[$language['code']]['title'] ?? '') }}"
                    />

                    <x-admin::form.row title="{{ __('page_category.text_summary') }}">
                      <div class="input-group">
                        @php
                          $is_invalid = $errors->has("descriptions.{$language['code']}.summary") ? 'is-invalid' : '';
                          $value = old('descriptions.' . $language['code'] . '.summary', $descriptions[$language['code']]['summary'] ?? '');
                        @endphp
                        <textarea rows="3" type="text" name="descriptions[{{ $language['code'] }}][summary]"
                                  class="form-control {{ $is_invalid }}"
                                  placeholder="{{ __('page_category.text_summary') }}">{{ $value }}</textarea>
                      </div>
                      @if ($errors->has("descriptions.{$language['code']}.summary"))
                        <span class="invalid-feedback d-block"
                              role="alert">{{ $errors->first("descriptions.{$language['code']}.summary") }}</span>
                      @endif
                    </x-admin::form.row>

                    <x-admin::form.row title="{{ __('admin/page.info_content') }}" required>
                      <div class="w-max-1000">
                        <textarea name="descriptions[{{ $language['code'] }}][content]" data-tinymce-height="600"
                                  class="form-control tinymce">
                          {{ old('descriptions.' . $language['code'] . '.content', $descriptions[$language['code']]['content'] ?? '') }}
                        </textarea>
                      </div>
                      @if ($errors->has("descriptions.{$language['code']}.content"))
                        <span class="invalid-feedback d-block"
                              role="alert">{{ $errors->first("descriptions.{$language['code']}.content") }}</span>
                      @endif
                    </x-admin::form.row>

                    <input type="hidden" name="descriptions[{{ $language['code'] }}][locale]"
                            value="{{ $language['code'] }}">
                    <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_title]"
                                        title="{{ __('admin/setting.meta_title') }}"
                                        value="{{ old('descriptions.' . $language['code'] . '.meta_title', $descriptions[$language['code']]['meta_title'] ?? '') }}"/>
                    <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_description]"
                                        title="{{ __('admin/setting.meta_description') }}"
                                        value="{{ old('descriptions.' . $language['code'] . '.meta_description', $descriptions[$language['code']]['meta_description'] ?? '') }}"/>
                    <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_keywords]"
                                        title="{{ __('admin/setting.meta_keywords') }}"
                                        value="{{ old('descriptions.' . $language['code'] . '.meta_keywords', $descriptions[$language['code']]['meta_keywords'] ?? '') }}"/>

                    @hook('admin.pages.form.content.form.after')
                  </div>
                @endforeach
                @hook('admin.page.edit.base.after')
              </div>

              @hook('admin.pages.form.main.content.after')
            </div>
          </div>

          @hook('admin.pages.form.main.after')
        </div>
        <div class="col-12 col-md-3">
          @hook('admin.pages.form.sidebar.before')

          <div class="card mb-3">
            <div class="card-body pb-0">
              <h5 class="mb-3">{{ __('common.status') }}</h5>

              @hook('admin.pages.form.sidebar.card.status.before')

              <x-admin-form-switch name="active" title="" :value="old('active', $page->active ?? 1)"/>

              @hook('admin.pages.form.sidebar.card.status.after')
            </div>
          </div>

          <div class="card mb-3">
            <div class="card-body">
              <h5 class="mb-3">{{ __('common.data') }}</h5>
              @hook('admin.page.data.before')

              <x-admin-form-input name="author" title="{{ __('page_category.author') }}"
                                  value="{{ old('author', $page->author ?? '') }}"/>
              <x-admin::form.row title="{{ __('admin/page_category.index') }}">
                <div class="">
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
                  <input type="hidden" name="page_category_id" :value="page_category_name ? page_category_id : ''"/>
                </div>
              </x-admin::form.row>

              <x-admin-form-image name="image" title="{{ __('admin/page.cover_picture') }}" value="{{ old('image', $page->image ?? '') }}">
                <div class="help-text font-size-12 lh-base">{{ __('common.recommend_size') }}: 500*350</div>
              </x-admin-form-image>

              <x-admin-form-input name="views" title="{{ __('page_category.views') }}" value="{{ old('views', $page->views ?? '') }}"/>

              @hook('admin.page.data.after')
            </div>
          </div>

          <div class="card mb-3">
            <div class="card-body">
              <h5 class="mb-3">{{ __('admin/product.product_relations') }}</h5>

              @hook('admin.pages.form.sidebar.card.product_relations.before')

              <div class="module-edit-group">
                <div class="autocomplete-group-wrapper">
                  <el-autocomplete
                    class="inline-input"
                    v-model="relations.keyword"
                    value-key="name"
                    popper-class="product-autocomplete-list"
                    size="small"
                    :fetch-suggestions="(keyword, cb) => {relationsQuerySearch(keyword, cb, 'products')}"
                    placeholder="{{ __('admin/builder.modules_keywords_search') }}"
                    @select="(e) => {handleSelect(e, 'product_relations')}"
                    >
                    <template slot-scope="{ item }">
                      <div class="product-item">
                        <div class="image"><img :src="item.image_format" class="img-fluid"></div>
                        <div class="name" v-text="item.name"></div>
                      </div>
                    </template>
                  </el-autocomplete>

                  <div class="item-group-wrapper" v-loading="relations.loading" v-if="relations.products.length">
                    <draggable
                      ghost-class="dragabble-ghost"
                      :list="relations.products"
                      :options="{animation: 330}"
                    >
                      <div v-for="(item, index) in relations.products" :key="index" class="item">
                        <div class="product-item">
                          <div class="image"><img :src="item.image_format || (item.images && item.images[0]) || ''" class="img-fluid"></div>
                          <span>@{{ item.name }}</span>
                        </div>
                        <i class="el-icon-delete right" @click="relationsRemoveProduct(index)"></i>
                        <input type="text" :name="'products['+ index +']'" v-model="item.id"
                                class="form-control d-none">
                      </div>
                    </draggable>
                  </div>
                </div>
              </div>

              @hook('admin.pages.form.sidebar.card.product_relations.after')
            </div>
          </div>

          @hook('admin.pages.form.sidebar.after')
        </div>
      </div>

      <button type="submit" class="d-none">{{ __('common.save') }}</button>
    </form>

    @hook('admin.pages.form.content.after')
  </div>

  @hook('admin.page.form.footer')
@endsection

@push('footer')
  <script>
    @hook('admin.pages.form.script.before')

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

        @hook('admin.pages.form.vue.data')
      },

      created() {
        const products = @json($page['products'] ?? []);
        if (products.length) {
          this.relations.products = products.map(v => {
            return {
              id: v.id,
              name: v.description.name,
              images: v.images,
            }
          })
        }

        @hook('admin.pages.form.vue.created')
      },

      methods: {
        relationsQuerySearch(keyword, cb, url) {
          $http.get(url + '/autocomplete?name=' + encodeURIComponent(keyword), null, {hload: true}).then((res) => {
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

        @hook('admin.pages.form.vue.methods')
      },

      @hook('admin.pages.form.vue.options')
    })

    @hook('admin.pages.form.script.after')
  </script>
@endpush



