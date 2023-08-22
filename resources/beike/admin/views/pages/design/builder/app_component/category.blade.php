<template id="module-editor-category-template">
  <div class="module-editor-category-template">
    <div class="module-editor-row">{{ __('admin/builder.text_set_up') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_module_title') }}</div>
      <text-i18n v-model="form.title"></text-i18n>
    </div>

    <div class="module-editor-row">{{ __('admin/builder.modules_content') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">搜索分类</div>
      <div class="tab-info">
        <div class="module-edit-group">
          <div class="autocomplete-group-wrapper">
            <el-autocomplete
              class="inline-input"
              v-model="keyword"
              value-key="name"
              size="small"
              :fetch-suggestions="querySearch"
              placeholder="{{ __('admin/builder.modules_keywords_search') }}"
              :highlight-first-item="true"
              @select="handleSelect"
            ></el-autocomplete>
          </div>
        </div>
      </div>
    </div>
    <div class="module-edit-group">
      <div class="module-edit-title">数量</div>
      <el-input v-model="form.limit" type="muner" size="small" @input="limitChange"></el-input>
    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-category', {
  delimiters: ['${', '}'],
  template: '#module-editor-category-template',
  props: ['module'],
  data: function () {
    return {
      keyword: '',
      productData: [],
      loading: null,
      form: null
    }
  },

  watch: {
    form: {
      handler: function (val) {
        this.$emit('on-changed', val);
      },
      deep: true
    },
  },

  created: function () {
    this.form = JSON.parse(JSON.stringify(this.module));
    this.tabsValueProductData();
  },

  computed: {
  },

  methods: {
    tabTitleLanguage(titles) {
      return titles['{{ locale() }}'];
    },

    tabsValueProductData() {
      var that = this;

      if (!this.form.products.length) return;
      this.loading = true;

      $http.get('products/names?product_ids='+this.form.products.map(e => e.id).join(','), {hload: true}).then((res) => {
        this.loading = false;
        that.productData = res.data;
      })
    },

    querySearch(keyword, cb) {
      $http.get('categories/autocomplete?name=' + encodeURIComponent(keyword), null, {hload:true}).then((res) => {
        cb(res.data);
      })
    },

    handleSelect(item) {
      this.form.category_id = item.id;
      this.form.category_name = item.name;
      this.getCategories();
    },

    limitChange(e) {
      this.form.limit = e;
      this.getCategories();
    },

    getCategories() {
      $http.get(`categories/${this.form.category_id}/products`, {limit: this.form.limit}, {hload:true}).then((res) => {
        this.form.products = res.data
      })
    },

    itemChange(evt) {
      this.form.products = this.productData
    },

    addTabData(type) {
      console.log(type);
    },

    removeProduct(index) {
      this.productData.splice(index, 1)
      this.form.products.splice(index, 1);
    },
  }
});
</script>

@push('footer')
  <script>
    app.source.modules.push({
      title: '{{__('admin/app_builder.module_category')}}',
      code: 'category',
      icon: '&#xe607;',
      content: {
        style: {
          background_color: ''
        },
        limit: 10,
        order: 'asc',
        category_id: '',
        category_name: '',
        sort: 'sales',
        floor: languagesFill(''),
        products: [],
        title: languagesFill('{{ __('admin/builder.text_module_title') }}'),
      }
    });
  </script>
@endpush
