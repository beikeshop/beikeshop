<template id="module-editor-product-template">
  <div class="module-editor-product-template">
    <div class="module-editor-row">{{ __('admin/builder.text_set_up') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_module_title') }}</div>
      <text-i18n v-model="form.title"></text-i18n>
    </div>

    <div class="module-editor-row">{{ __('admin/builder.modules_content') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">数据来源</div>

      <el-radio-group v-model="module.data_type" size="small" @change="addTabData">
        <el-radio-button label="product">自定义商品</el-radio-button>
        <el-radio-button label="category">分类商品</el-radio-button>
        <el-radio-button label="latest">最新商品</el-radio-button>
      </el-radio-group>
    </div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.modules_set_product') }}</div>
      <div class="tab-info">
        <div class="module-edit-group">
          <div class="autocomplete-group-wrapper">
            <el-autocomplete
              class="inline-input"
              v-model="keyword"
              v-if="module.data_type == 'product' || module.data_type == 'category'"
              value-key="name"
              size="small"
              :fetch-suggestions="querySearch"
              placeholder="{{ __('admin/builder.modules_keywords_search') }}"
              :highlight-first-item="true"
              @select="handleSelect"
            ></el-autocomplete>

            <div class="item-group-wrapper" v-loading="loading" v-if="module.data_type == 'product'">
              <template v-if="productData.length">
                <draggable
                  ghost-class="dragabble-ghost"
                  :list="productData"
                  @change="itemChange"
                  :options="{animation: 330}"
                >
                  <div v-for="(item, index) in productData" :key="index" class="item">
                    <div>
                      <i class="el-icon-s-unfold"></i>
                      <span>${item.name}</span>
                    </div>
                    <i class="el-icon-delete right" @click="removeProduct(index)"></i>
                  </div>
                </draggable>
              </template>
              <template v-else>{{ __('admin/builder.modules_please_products') }}</template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-product', {
  delimiters: ['${', '}'],
  template: '#module-editor-product-template',
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

    handleSelect(item) {
      if (this.form.data_type == 'product') {
        if (!this.form.products.find(v => v == item.id)) {
          this.form.products.push(item);
          this.productData.push(item);
        }

        this.keyword = ""
      }

      if (this.form.data_type == 'category') {
        this.form.category_id = item.id;
        this.form.category_name = item.name;


        // $http.get(`categories/${item.id}/name`, null, {hload:true}).then((res) => {
          // console.log(res.data);
        // })
      }
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
      title: '{{__('admin/builder.modules_product')}}',
      code: 'product',
      icon: '&#xe607;',
      content: {
        style: {
          background_color: ''
        },
        data_type: 'product',
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
