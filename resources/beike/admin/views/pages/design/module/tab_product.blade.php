<template id="module-editor-tab-product-template">
  <div class="module-editor-tab-product-template">
    <div class="module-editor-row">设置</div>
    <div class="module-edit-group">
      <div class="module-edit-title">模块标题</div>
      <text-i18n v-model="module.title"></text-i18n>
    </div>

    <div class="module-editor-row">内容</div>
    <div class="module-edit-group">
      <div class="module-edit-title">配置商品</div>
      <el-tabs v-model="editableTabsValue" class="tab-edit-category" type="card" editable @edit="handleTabsEdit">
        <el-tab-pane
          v-for="(item, index) in module.tabs"
          :key="index"
          :label="tabTitleLanguage(item.title)"
          :name="index + ''"
        >

        <div class="tab-info">
          <div class="module-edit-group">
            <div class="module-edit-title">配置标题</div>
            <text-i18n v-model="item.title"></text-i18n>
          </div>

          <div class="module-edit-group">
            <div class="module-edit-title">商品</div>

            <div class="autocomplete-group-wrapper">
              <el-autocomplete
                class="inline-input"
                v-model="keyword"
                value-key="name"
                size="small"
                :fetch-suggestions="querySearch"
                placeholder="请输入关键字搜索"
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
                      <span>${item.name}</span>
                      <i class="fa fa-minus-circle" @click="removeProduct(index)"></i>
                    </div>
                  </draggable>
                </template>
                <template v-else>请添加商品</template>
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
      return titles['{{ current_language_code() }}'];
    },

    tabsValueProductData() {
      var that = this;

      if (!this.module.tabs[0].products.length) return;
      this.loading = true;

      http://beike.test/panel/products/names?product_ids=1,2,3

      $http.get('panel/products/names', {product_ids: this.module.tabs[this.editableTabsValue].products}, {hload: true}).then((res) => {
        console.log(res)
      })

      // $.ajax({
      //   url: 'index.php?route=extension/theme/default/page/module/product/productsByIds',
      //   data: {product_ids: this.module.tabs[this.editableTabsValue].products},
      //   type: 'post',
      //   dataType: 'json',
      //   success: function (json) {
      //     if (json) {
      //       that.loading = false;
      //       that.productData = json;
      //     }
      //   }
      // });
    },

    querySearch(keyword, cb) {
      if (!keyword) {
        return;
      }

      $http.get('products/autocomplete', {name: keyword}, null).then((res) => {
        cb(res);
      })
    },

    handleSelect(item) {
      if (!this.module.tabs[this.editableTabsValue].products.find(v => v == item.product_id)) {
        this.module.tabs[this.editableTabsValue].products.push(item.product_id * 1);
        this.productData.push(item);
      }
      this.keyword = ""
    },

    itemChange(evt) {
      this.module.tabs[this.editableTabsValue].products = this.productData.map(e => e.product_id * 1);
    },

    removeProduct(index) {
      this.productData.splice(index, 1)
      this.module.tabs[this.editableTabsValue].products.splice(index, 1);
    },

    handleTabsEdit(targetName, action) {
      if (action === 'add') {
        this.module.tabs.push({title: languagesFill('标题'), products: []});
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
    tabs: [{title: languagesFill('标题'), products: []}],
    title: languagesFill('模块标题'),
  }

  let register = @json($register);

  register.make = make;
  app.source.modules.push(register)
}, 100)
</script>
