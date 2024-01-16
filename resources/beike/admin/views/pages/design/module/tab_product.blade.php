<template id="module-editor-tab-product-template">
  <div class="module-editor-tab-product-template">
    <div class="module-editor-row">{{ __('admin/builder.text_set_up') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_module_title') }}</div>
      <text-i18n v-model="form.title"></text-i18n>
    </div>

    <div class="module-editor-row">{{ __('admin/builder.modules_content') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.modules_set_product') }}</div>
      <el-tabs v-model="form.editableTabsValue" class="tab-edit-category" type="card" editable @edit="handleTabsEdit">
        <el-tab-pane
          v-for="(item, index) in form.tabs"
          :key="index"
          :label="tabTitleLanguage(item.title)"
          :name="index + ''"
        >

        <div class="tab-info">
          <div class="module-edit-group">
            <div class="module-edit-title">{{ __('admin/builder.text_set_title') }}</div>
            <text-i18n v-model="item.title"></text-i18n>
          </div>

          <div class="module-edit-group">
            <div class="module-edit-title">{{ __('admin/builder.modules_product') }}</div>

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

              <div class="item-group-wrapper" v-loading="loading">
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
        </el-tab-pane>
      </el-tabs>
    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-tab-product', {
  delimiters: ['${', '}'],
  template: '#module-editor-tab-product-template',
  props: ['module'],
  data: function () {
    return {
      keyword: '',
      productData: [],
      loading: null,
      form: null,
    }
  },

  watch: {
    form: {
      handler: function (val) {
        this.$emit('on-changed', val);
      },
      deep: true
    },
    'form.editableTabsValue'() {
      this.productData = [];
      this.tabsValueProductData();
    }
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

      if (!this.form.tabs[this.form.editableTabsValue].products.length) return;
      this.loading = true;

      $http.get('products/names?product_ids='+this.form.tabs[this.form.editableTabsValue].products.join(','), {hload: true}).then((res) => {
        this.loading = false;
        this.form.tabs[this.form.editableTabsValue].products = res.data.map(e => e.id);
        that.productData = res.data;
      })
    },

    querySearch(keyword, cb) {
      $http.get('products/autocomplete?name=' + encodeURIComponent(keyword), null, {hload:true}).then((res) => {
        cb(res.data);
      })
    },

    handleSelect(item) {
      if (!this.form.tabs[this.form.editableTabsValue].products.find(v => v == item.id)) {
        this.form.tabs[this.form.editableTabsValue].products.push(item.id * 1);
        this.productData.push(item);
      }
      this.keyword = ""
    },

    itemChange(evt) {
      this.form.tabs[this.form.editableTabsValue].products = this.productData.map(e => e.id * 1);
    },

    removeProduct(index) {
      this.productData.splice(index, 1)
      this.form.tabs[this.form.editableTabsValue].products.splice(index, 1);
    },

    handleTabsEdit(targetName, action) {
      if (action === 'add') {
        this.form.tabs.push({title: languagesFill('Tab ' + (this.form.tabs.length + 1)), products: []});
        this.form.editableTabsValue = this.form.tabs.length - 1 + '';
      }

      if (action === 'remove') {
        let tabs = this.form.tabs;
        tabs.splice(targetName, 1);
        let activeName = this.form.editableTabsValue == 0 ? '0' : targetName * 1 - 1 + '';

        this.form.editableTabsValue = activeName;
        this.form.tabs = tabs.filter(tab => tab.name !== targetName);
      }
    }
  }
});
</script>

@push('footer-script')
  <script>
    register = @json($register);

    // 定义模块的配置项
    register.make = {
      style: {
        background_color: ''
      },
      editableTabsValue: '0',
      floor: languagesFill(''),
      tabs: [{title: languagesFill('Tab 1'), products: []}],
      title: languagesFill('{{ __('admin/builder.text_module_title') }}'),
    }

    app.source.modules.push(register)
  </script>
@endpush
