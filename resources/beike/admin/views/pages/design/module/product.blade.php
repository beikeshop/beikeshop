<template id="module-editor-product-template">
  <div class="module-editor-product-template">
    <div class="module-editor-title">设置</div>
    <div class="module-edit-group">
      <div class="module-edit-title">设置标题</div>
      <text-i18n v-model="module.title"></text-i18n>
    </div>

    <div class="module-editor-title">内容</div>
    <div class="module-edit-group">
      <div class="module-edit-title">设置商品</div>

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
          <draggable
            ghost-class="dragabble-ghost"
            :list="productData"
            @change="itemChange"
            :options="{animation: 330}"
          >
            <div v-for="(item, index) in productData" :key="index" class="item">
              <span>@{{ item.name }}</span>
              <i class="fa fa-minus-circle" @click="removeProduct(index)"></i>
            </div>
          </draggable>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-product', {
  template: '#module-editor-product-template',
  props: ['module'],
  data: function () {
    return {
      keyword: '',
      productData: [],
      loading: null,
    }
  },

  watch: {
    module: {
      handler: function (val) {
        this.$emit('on-changed', val);
      },
      deep: true
    }
  },

  created: function () {
    var that = this;
    if (!this.module.items.length) return;
    this.loading = true;

    $.ajax({
      url: 'index.php?route=extension/theme/default/page/module/product/productsByIds',
      data: {product_ids: this.module.items},
      type: 'post',
      dataType: 'json',
      success: function (json) {
        if (json) {
          that.loading = false;
          that.productData = json;
        }
      }
    });
  },
  methods: {
    querySearch(keyword, cb) {
      if (!keyword) {
        return;
      }

      $http.get(`products/${keyword}/name`, null, {hload: true}).then((res) => {
        if (res.data) {
          cb(res.data);
        }
      })
    },

    handleSelect(item) {
      if (!this.module.items.find(v => v == item.product_id)) {
        this.module.items.push(item.product_id * 1);
        this.productData.push(item);
      }
      this.keyword = ""
    },

    itemChange(evt) {
      this.module.items = this.productData.map(e => e.product_id * 1);
    },

    removeProduct(index) {
      this.productData.splice(index, 1)
      this.module.items.splice(index, 1);
    }
  }
});

setTimeout(() => {
  const make = {
    style: {
      background_color: ''
    },
    floor: languagesFill(''),
    items: [0],
    title: languagesFill('商品模块'),
  }

  let register = @json($register);

  register.make = make;
  app.source.modules.push(register)
}, 100)
</script>
