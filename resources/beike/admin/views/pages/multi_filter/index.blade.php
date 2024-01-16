@extends('admin::layouts.master')

@section('title', __('admin/common.multi_filter_index'))

@section('content')
<div class="card">
  <div class="card-body h-min-600">
    <form action="{{ admin_route('multi_filter.store') }}" class="needs-validation" novalidate method="POST" id="app">
      @csrf
      @if (session('success'))
        <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
      @endif
      <h6 class="border-bottom pb-3 mb-4">{{ __('common.data') }}</h6>

      <x-admin::form.row :title="__('admin/setting.filter_attribute')">
        <div class="module-edit-group wp-600">
          <div class="autocomplete-group-wrapper">
            <el-autocomplete class="inline-input" v-model="multi_filter.keyword" value-key="name" size="small"
              :fetch-suggestions="(keyword, cb) => {attributesQuerySearch(keyword, cb, 'products')}"
              placeholder="{{ __('admin/builder.modules_keywords_search') }}"
              @select="(e) => {handleSelect(e, 'product_attributes')}"></el-autocomplete>

            <div class="item-group-wrapper" v-loading="multi_filter.loading">
              <div v-for="(item, index) in multi_filter.filters.attribute" :key="index" class="item">
                <div>
                  <i class="el-icon-s-unfold"></i>
                  <span>@{{ item.name }}</span>
                </div>
                <i class="el-icon-delete right" @click="attributesRemoveProduct(index)"></i>
                <input type="text" :name="'multi_filter[attribute]['+ index +']'" v-model="item.id"
                  class="form-control d-none">
              </div>
            </div>
            <div class="help-text font-size-12 lh-base">{{ __('admin/setting.multi_filter_helper') }}</div>
          </div>
        </div>
      </x-admin::form.row>

      <x-admin-form-switch name="multi_filter[price_filter]" :title="__('admin/multi_filter.price_filter')" :value="old('price_filter', $multi_filter['price_filter'] ?? 1)" />

      <x-admin::form.row title="">
        <button class="btn btn-lg btn-primary mt-5">{{ __('common.save') }}</button>
      </x-admin::form.row>
    </form>
  </div>
</div>
@endsection

@push('footer')
  <script>
    let app = new Vue({
      el: '#app',
      data: {
        multi_filter: {
          keyword: '',
          filters: @json($multi_filter ?? null),
          loading: null,
        },

        source: {
          mailEngines: [
            {name: '{{ __('admin/builder.text_no') }}', code: ''},
            {name: 'SMTP', code: 'smtp'},
            {name: 'Sendmail', code: 'sendmail'},
            {name: 'Mailgun', code: 'mailgun'},
            {name: 'Log', code: 'log'},
          ]
        },
      },
      methods: {
        attributesQuerySearch(keyword, cb, url) {
          $http.get('attributes/autocomplete?name=' + encodeURIComponent(keyword), null, {hload:true}).then((res) => {
            cb(res.data);
          })
        },

        attributesRemoveProduct(index) {
          this.multi_filter.filters.attribute.splice(index, 1);
        },

        handleSelect(item, key) {
          if (key == 'product_attributes') {
            if (!this.multi_filter.filters.attribute.find(v => v.id * 1 == item.id * 1)) {
              this.multi_filter.filters.attribute.push(item);
            } else {
              layer.msg('{{ __('common.no_repeat') }}', () => {})
            }

            this.multi_filter.keyword = ""
          }
        },
      }
    });
  </script>
@endpush



