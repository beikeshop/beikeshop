<template id="link-selector">
  <div class="link-selector-wrap">
    <div class="title" v-if="isTitle"><i class="el-icon-link"></i>{{ __('admin/builder.modules_choose_link') }}</div>
    <div class="selector-type" @blur="selectorContentShow = false" tabindex="1">
      <div class="title" v-if="link.type != 'custom' ? value.value == '' : ''" @click="selectorContentShow = !selectorContentShow">{{ __('admin/builder.modules_choose_link') }}</div>
      <div class="title" @click="selectorContentShow = !selectorContentShow" v-else :title="name" v-loading="nameLoading">@{{ selectorTitle }}: @{{ name }}</div>
      <div :class="'selector-content ' + (selectorContentShow ? 'active' : '')">
        <div @click="selectorType()">{{ __('admin/builder.text_no') }}</div>
        <div v-for="(type, index) in types" :key="index" @click="selectorType(type.type)">@{{ type.label }}</div>
      </div>
    </div>

    <el-dialog
      :visible.sync="linkDialog.show"
      class="link-dialog-box"
      :append-to-body="true"
      :close-on-click-modal="false"
      @open="linkDialogOpen"
      @closed="linkDialogClose"
      width="460px">
      <div slot="title" class="link-dialog-header">
        <div class="title">{{ __('admin/builder.modules_choose') }}@{{ dialogTitle }}</div>
        <div class="input-with-select" v-if="link.type != 'custom'">
          <input type="text" placeholder="{{ __('admin/builder.modules_keywords_search') }}" v-model="keyword" @keyup.enter="searchProduct" class="form-control">
          <el-button  @click="searchProduct"><i class="el-icon-search"></i> {{ __('admin/builder.text_search') }}</el-button>
        </div>
      </div>
      <div class="link-dialog-content">
        <div class="product-search">
          <div class="link-top-new">
            <span>{{ __('admin/builder.text_is_newpage') }}</span>
            <el-switch :width="36" @change="linksNewBack" v-model="link.new_window"></el-switch>
          </div>

          <a :href="linkTypeAdmin" target="_blank" v-if="link.type != 'custom' && link.type != 'static'">{{ __('admin/builder.text_manage') }}@{{ dialogTitle }}</a>
        </div>

        <div class="link-text" v-if="isCustomName">
          <div class="module-edit-group" style="margin-bottom: 10px;">
            <div class="module-edit-title">{{ __('admin/builder.custom_name') }}</div>
            <text-i18n v-model="link.text"></text-i18n>
          </div>
        </div>
        <template v-if="link.type == 'custom'">
          <div class="linkDialog-custom">
            <el-input v-model="link.value" placeholder="{{ __('admin/builder.text_enter_link') }}"></el-input>
          </div>
        </template>
        <template v-else-if="link.type == 'static'">
          <div class="">
            <div class="product-info">
              <ul class="product-list static">
                <li v-for="(product, index) in static" @click="link.value = product.value">
                  <div class="left">
                    <span :class="'checkbox-plus ' + (link.value == product.value ? 'active':'')"></span>
                    <div>@{{ product.name }}</div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="product-info" v-loading="loading">
            <template v-if="linkDialog.data.length">
              <div class="product-info-title">
                <span>{{ __('admin/builder.modules_content') }}</span>
                <span>{{ __('admin/builder.text_states') }}</span>
              </div>

              <ul class="product-list">
                <li v-for="(product, index) in linkDialog.data" @click="product.status ? link.value = product.id : false" :class="!product.status ? 'no-status' : ''">
                  <div class="left">
                    <span :class="'checkbox-plus ' + (link.value == product.id ? 'active':'') + (!product.status ? 'no-status':'')"></span>
                    <img :src="product.image" v-if="product.image" class="img-responsive">
                    <div>@{{ product.name }}</div>
                  </div>
                  <div :class="'right ' + (product.status ? 'ok' : 'no')">
                    <template v-if="product.status">{{ __('admin/builder.text_enable') }}</template>
                    <template v-else>{{ __('admin/builder.text_disable') }}</template>
                  </div>
                </li>
              </ul>
            </template>
            <div class="product-info-no" v-if="!linkDialog.data.length && loading === false">
              <div class="icon"><i class="iconfont">&#xe60c;</i></div>
              <div class="no-text">{{ __('admin/builder.text_no_data') }}, <a :href="linkTypeAdmin" target="_blank">{{ __('admin/builder.text_to_add') }}@{{ dialogTitle }}</a></div>
            </div>
          </div>
        </template>
      </div>
      <div slot="footer" class="link-dialog-footer">
        <el-button type="primary" @click="linkDialogConfirm">{{ __('admin/builder.text_sure') }}</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script type="text/javascript">
  Vue.component('link-selector', {
    template: '#link-selector',

    props: {
      value: {
        default: null
      },

      isTitle: {
        default: true,
        type: Boolean
      },

      isCustomName: {
        default: false,
        type: Boolean
      },

      showText: {
        default: false
      },

      hideTypes: {
        type: Array,
        default: function () {
          return [];
        }
      },

      type: {
        default: null
      },

      linkNew: {
        default: true
      },
    },

    data: function () {
      return {
        types: [
          {type: 'product', label: '{{ __('admin/builder.modules_product') }}'},
          {type: 'category', label: '{{ __('admin/builder.text_category') }}'},
          {type: 'page', label: '{{ __('admin/builder.text_information') }}'},
          {type: 'page_category', label: '{{ __('admin/builder.page_category') }}'},
          {type: 'brand', label: '{{ __('admin/builder.text_manufacturer') }}'},
          {type: 'static',label: '{{ __('admin/builder.text_static') }}'},
          {type: 'custom',label: '{{ __('admin/builder.text_custom') }}'}
        ],
        static: [
          {name: '{{ __('shop/account.index') }}', value: 'account.index'},
          {name: '{{ __('shop/account/wishlist.index') }}', value: 'account.wishlist.index'},
          {name: '{{ __('shop/account/order.index') }}', value: 'account.order.index'},
          // {name: '最新商品', value: 'account.index'},
          {name: '{{ __('shop/brands.index') }}', value: 'brands.index'},
        ],
        link: null,
        keyword: '',
        name: '',
        locale: '{{ locale() }}',
        loading: null,
        nameLoading: null,
        selectorContentShow: false,
        isUpdate: true,
        linkDialog: {
          show: false,
          data:[],
        }
      }
    },

    beforeMount() {
      this.updateData();
      if (this.hideTypes.length) {
        this.types = this.types.filter((item) => {
          return this.hideTypes.indexOf(item.type) == -1;
        });
      }
    },

    watch: {
      value() {
        if (this.isUpdate) {
          this.updateData();
        }
      }
    },

    computed: {
      dialogTitle: function () {
        return this.types.find(e => e.type == this.link.type).label;
      },

      selectorTitle() {
        return this.types.find(e => e.type == this.value.type).label;
      },

      linkTypeAdmin: function () {
        let url = '';

        switch(this.link.type) {
          case 'product':
            url = '{{ admin_route('products.index') }}';
            break;
          case 'category':
            url = '{{ admin_route('categories.index') }}';
            break;
          case 'brand':
            url = '{{ admin_route('brands.index') }}';
            break;
          case 'page':
            url = '{{ admin_route('pages.index') }}';
            break;
          case 'page_category':
            url = '{{ admin_route('page_categories.index') }}';
            break;
          default:
            null;
        }
        return url;
      },
    },

    methods: {
      linkDialogConfirm() {
        this.isUpdate = false;
        if (this.link.type == 'custom') {
          this.name = this.link.value;
        } else if (this.link.type == 'static') {
          this.name = this.static.find(e => e.value == this.link.value).name;
        } else {
          this.name = this.linkDialog.data.find(e => e.id == this.link.value).name;
        }

        let links = JSON.parse(JSON.stringify(this.link)); // type 类型切换时，不需要更新视图
        this.$emit("input", links);
        this.linkDialog.show = false;
        this.$nextTick(() => {
          this.isUpdate = true;
        })
      },

      searchProduct() {
        const self = this;
        this.link.value = '';
        this.querySearch(this.keyword, null, function (data) {
          self.linkDialog.data = data.data;
        })
      },

      linkDialogClose() {
        this.linkDialog.data = [];
      },

      linkDialogOpen() {
        const self = this;
        this.keyword = '',
        this.selectorContentShow = false;
        if (this.link.type != 'custom' || this.value.type != 'custom') {
          this.link.value = ''
        }

        if (this.link.type == 'custom' || this.link.type == 'static') {
          return;
        }

        this.querySearch(this.keyword, 'all', function (data) {
          self.linkDialog.data = data.data;
        })
      },

      selectorType(type) {
        if (type) {
          this.linkDialog.show = true;
          this.link.type = type;

          if (type == 'custom') {
            if (this.link.text) {
              this.link.text = this.link.text
            } else {
              this.link.text = languagesFill('')
            }
          }
          return;
        }

        this.selectorContentShow = false;
        this.$emit("input", {link: '', type: 'category', value: ''});
      },

      querySearch(keyword, all, cb) {
        const self = this;
        let url = '';

        switch(this.link.type) {
          case 'product':
            url = 'products/autocomplete?name=';
            break;
          case 'category':
            url = 'categories/autocomplete?name=';
            break;
          case 'brand':
            url = 'brands/autocomplete?name=';
            break;
          case 'page':
            url = 'pages/autocomplete?name=';
            break;
          case 'page_category':
            url = 'page_categories/autocomplete?name=';
            break;
          default:
            null;
        }

        this.loading = true;

        $http.get(url + encodeURIComponent(keyword), null, {hload: true}).then((res) => {
          if (res) {cb(res)};
          this.loading = false;
        }).finally(() => {this.loading = false});
      },

      linksNewBack() {
        let links = JSON.parse(JSON.stringify(this.link));
        this.$emit("input", links);
      },

      updateData() {
        this.value.type = this.value?.type || 'category';
        this.value.link = this.value?.link || '';
        this.link = JSON.parse(JSON.stringify(this.value));
        if (this.type) {
          this.types = this.types.filter(e => e.type == this.type);
        }

        if (this.link.type == 'custom') return this.name = this.link.value || this.link.text[this.locale] || '';

        if (!this.link.value) return;
        if (this.link.type == 'static') {
          if (this.static.find(e => e.value == this.link.value)) {
            this.name = this.static.find(e => e.value == this.link.value).name;
          }

          return;
        }

        this.nameLoading = true;

        let self = this, url = '', data = {};

        switch(this.link.type) {
          case 'product':
            url = `products/${this.link.value}/name`;
            break;
          case 'category':
            url = `categories/${this.link.value}/name`;
            break;
          case 'brand':
            url = `brands/${this.link.value}/name`;
            break;
          case 'page':
            url = `pages/${this.link.value}/name`;
            break;
          case 'page_category':
            url = `page_categories/${this.link.value}/name`;
            break;
          default:
            null;
        }

        $http.get(url, null, {hload: true, hmsg: true}).then((res) => {
          if (res.data) {
            self.name = res.data;
          } else {
            self.name = '{{ __('admin/builder.text_no_data') }}';
          }
        }).catch(() => {
          self.name = '{{ __('admin/builder.text_no_data') }}';
        }).finally(() => {
          self.nameLoading = false;
        });
      },
    }
  });
</script>
<style>
  .link-text {
    margin-bottom: 5px;
  }
  .link-text .text-i18n-template .el-tabs__nav > div {
    height: 26px;
    line-height: 26px;
  }
  .link-text .el-tabs {
    margin-top: 10px;
  }

  .link-text .el-tabs__header {
    margin: 0;
  }

  .el-collapse-item__wrap {
    overflow: initial;
  }
</style>
