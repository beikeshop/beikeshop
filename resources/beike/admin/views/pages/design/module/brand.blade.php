<template id="module-editor-brand-template">
  <div class="image-edit-wrapper">
    <div class="module-editor-row">设置</div>
    <div class="module-edit-group">
      <div class="module-edit-title">模块标题</div>
      <text-i18n v-model="module.title"></text-i18n>
    </div>

    <div class="module-editor-row">内容</div>
    <div class="module-edit-group">
      <div class="module-edit-title">选择品牌</div>

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
            :list="brands"
            @change="itemChange"
            :options="{animation: 330}"
          >
            <div v-for="(item, index) in brands" :key="index" class="item">
              <div>
                <i class="el-icon-s-unfold"></i>
                <span>@{{ item.name }}</span>
              </div>
              <i class="el-icon-delete right" @click="removeProduct(index)"></i>
            </div>
          </draggable>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-brand', {
  template: '#module-editor-brand-template',

  props: ['module'],

  data: function () {
    return {
      loading: null,
      brands: [],
      keyword: '',
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
    if (!this.module.brands.length) return;
    const ids = this.module.brands.join(',');
    this.loading = true;
    $http.get(`brands/names?ids=${ids}`, null, {hload:true}).then((res) => {
      console.log(res)
    }).finally(e => this.loading = false)
  },

  methods: {
    querySearch(keyword, cb) {
      if (!keyword) {
        return;
      }

      $http.get('brands/autocomplete?name=' + encodeURIComponent(keyword), null, {hload:true}).then((res) => {
        cb(res.data);
      })
    },

    handleSelect(item) {
      if (!this.module.brands.find(v => v == item.id)) {
        this.module.brands.push(item.id * 1);
        this.brands.push(item);
      }
      this.keyword = ""
    },

    removeProduct(index) {
      this.brands.splice(index, 1)
      this.module.brands.splice(index, 1);
    },

    itemChange(evt) {
      this.module.brands = this.brands.map(e => e.id * 1);
    },
  }
});

setTimeout(() => {
  const make = {
    style: {
      background_color: ''
    },
    floor: languagesFill(''),
    full: true,
    title: languagesFill('推荐品牌模块'),
    brands: []
  }

  let register = @json($register);

  register.make = make;
  app.source.modules.push(register)
}, 100)
</script>
