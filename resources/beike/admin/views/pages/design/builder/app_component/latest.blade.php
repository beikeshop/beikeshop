<template id="module-editor-latest-template">
  <div class="module-editor-latest-template">
    <div class="module-editor-row">{{ __('admin/builder.text_set_up') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_module_title') }}</div>
      <text-i18n v-model="form.title"></text-i18n>
    </div>

    <div class="module-editor-row">{{ __('admin/builder.modules_content') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">数量</div>
      <el-input v-model="form.limit" type="muner" size="small"></el-input>
    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-latest', {
  delimiters: ['${', '}'],
  template: '#module-editor-latest-template',
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
      const url = this.form.data_type == 'product' ? 'products/autocomplete' : 'categories/autocomplete';
      $http.get(url + '?name=' + encodeURIComponent(keyword), null, {hload:true}).then((res) => {
        cb(res.data);
      })
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
      title: '{{__('admin/app_builder.module_latest')}}',
      code: 'latest',
      icon: '&#xe607;',
      content: {
        style: {
          background_color: ''
        },
        limit: 10,
        floor: languagesFill(''),
        products: [],
        title: languagesFill('{{ __('admin/builder.text_module_title') }}'),
      }
    });
  </script>
@endpush
