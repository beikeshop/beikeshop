<template id="module-editor-tab-product-template">
  <div class="module-editor-tab-product-template">
    <div class="module-editor-row">{{ __('admin/builder.text_set_up') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_module_title') }}</div>
      <text-i18n v-model="module.title"></text-i18n>
    </div>

    <div class="module-editor-row">{{ __('admin/builder.modules_content') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.modules_set_product') }}</div>
      <el-tabs v-model="editableTabsValue" class="tab-edit-category" type="card" editable @edit="handleTabsEdit">
        <el-tab-pane
          v-for="(item, index) in module.tabs"
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
                :trigger-on-focus="false"
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
      editableTabsValue: '0',
    }
  },

  watch: {
    module: {
      handler: function (val) {
        this.$emit('on-changed', val);
      },
      deep: true
    },
    editableTabsValue() {
      this.productData = [];
      this.tabsValueProductData();
    }
  },

  created: function () {
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

      if (!this.module.tabs[0].products.length) return;
      this.loading = true;

      $http.get('products/names?product_ids='+this.module.tabs[this.editableTabsValue].products.join(','), {hload: true}).then((res) => {
        this.loading = false;
        that.productData = res.data;
      })
    },

    querySearch(keyword, cb) {
      if (!keyword) {
        return;
      }

      $http.get('products/autocomplete?name=' + encodeURIComponent(keyword), null, {hload:true}).then((res) => {
        cb(res.data);
      })
    },

    handleSelect(item) {
      if (!this.module.tabs[this.editableTabsValue].products.find(v => v == item.id)) {
        this.module.tabs[this.editableTabsValue].products.push(item.id * 1);
        this.productData.push(item);
      }
      this.keyword = ""
    },

    itemChange(evt) {
      this.module.tabs[this.editableTabsValue].products = this.productData.map(e => e.id * 1);
    },

    removeProduct(index) {
      this.productData.splice(index, 1)
      this.module.tabs[this.editableTabsValue].products.splice(index, 1);
    },

    handleTabsEdit(targetName, action) {
      if (action === 'add') {
        this.module.tabs.push({title: languagesFill('Tab ' + (this.module.tabs.length + 1)), products: []});
        this.editableTabsValue = this.module.tabs.length - 1 + '';
      }

      if (action === 'remove') {
        let tabs = this.module.tabs;
        tabs.splice(targetName, 1);
        let activeName = this.editableTabsValue == 0 ? '0' : targetName * 1 - 1 + '';

        this.editableTabsValue = activeName;
        this.module.tabs = tabs.filter(tab => tab.name !== targetName);
      }
    }
  }
});

setTimeout(() => {
  const make = {
    style: {
      background_color: ''
    },
    floor: languagesFill(''),
    tabs: [{title: languagesFill('Tab 1'), products: []}],
    title: languagesFill('{{ __('admin/builder.text_module_title') }}'),
  }

  let register = @json($register);

  register.make = make;
  app.source.modules.push(register)
}, 100)
</script>
