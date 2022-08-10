<template id="link-selector">
  <div class="link-selector-wrap">
    <div class="title" v-if="isTitle"><i class="el-icon-link"></i>选择链接</div>
    <div class="selector-type" @blur="selectorContentShow = false" tabindex="1">
      <div class="title" v-if="value.value == ''" @click="selectorContentShow = !selectorContentShow">请选择跳转到的链接页面</div>
      <div class="title" @click="selectorContentShow = !selectorContentShow" v-else :title="name" v-loading="nameLoading">@{{ selectorTitle }}: @{{ name }}</div>
      <div :class="'selector-content ' + (selectorContentShow ? 'active' : '')">
        <div @click="selectorType()">无</div>
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
        <div class="title">选择@{{ dialogTitle }}</div>
        <div class="input-with-select" v-if="link.type != 'custom'">
          <input type="text" placeholder="请输入关键字搜索" v-model="keyword" @keyup.enter="searchProduct" class="form-control">
          <el-button  @click="searchProduct"><i class="el-icon-search"></i> 搜索</el-button>
        </div>
      </div>
      <div class="link-dialog-content">
        <div class="product-search">
          <div class="link-top-new">
            <span>是否新窗口打开</span>
            <el-switch :width="36" @change="linksNewBack" v-model="link.new_window"></el-switch>
          </div>

          <a :href="linkTypeAdmin" target="_blank" v-if="link.type != 'custom' && link.type != 'static'">管理@{{ dialogTitle }}</a>
        </div>

        <div class="link-text" v-if="showText">
          <div class="module-edit-group" style="margin-bottom: 10px;">
            <div class="module-edit-title">标题（覆盖下方选择的链接名称）</div>
            <text-i18n v-model="link.text"></text-i18n>
          </div>
        </div>

        <template v-if="link.type == 'custom'">
          <div class="linkDialog-custom">
            <el-input v-model="link.value" placeholder="请输入链接地址"></el-input>
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
                <span>内容</span>
                <span>状态</span>
              </div>

              <ul class="product-list">
                <li v-for="(product, index) in linkDialog.data" @click="product.status ? link.value = product.id : false" :class="!product.status ? 'no-status' : ''">
                  <div class="left">
                    <span :class="'checkbox-plus ' + (link.value == product.id ? 'active':'') + (!product.status ? 'no-status':'')"></span>
                    <img :src="product.image" v-if="product.image" class="img-responsive">
                    <div>@{{ product.name }}</div>
                  </div>
                  <div :class="'right ' + (product.status ? 'ok' : 'no')">@{{ product.status ? '启用' : '禁用' }}</div>
                </li>
              </ul>
            </template>
            <div class="product-info-no" v-if="!linkDialog.data.length && loading === false">
              <div class="icon"><i class="iconfont">&#xe60c;</i></div>
              <div class="no-text">数据不存在或已被删除，<a :href="linkTypeAdmin" target="_blank">去添加@{{ dialogTitle }}</a></div>
            </div>
          </div>
        </template>
      </div>
      <div slot="footer" class="link-dialog-footer">
        <el-button type="primary" @click="linkDialogConfirm" :disabled="link.value == ''">确 定</el-button>
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
          {type: 'product',label: '商品'},
          {type: 'category',label: '分类'},
          {type: 'page',label: '信息页面'},
          {type: 'brand', label: '品牌'},
          {type: 'static',label: '固定连接'},
          // {type: 'blog',label: '博客'},
          {type: 'custom',label: '自定义'}
        ],
        static: [
          {name:'个人中心', value: 'account/latest'},
        ],
        link: null,
        keyword: '',
        name: '',
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
            url = '';
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

        if (!this.link.value) return;
        if (this.link.type == 'custom') return this.name = this.link.value;
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
          default:
            null;
        }

        $http.get(url, null, {hload: true}).then((res) => {
          if (res.data) {
            self.name = res.data;
          } else {
            self.name = '数据不存在或已被删除';
          }
        }).finally(() => {this.nameLoading = false});
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
