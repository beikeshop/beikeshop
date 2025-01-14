<template id="module-editor-page-template">
  <div class="module-editor-page-template">
    <module-size v-model="form.module_size"></module-size>

    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_module_title') }}</div>
      <text-i18n v-model="form.title"></text-i18n>
    </div>

    <div class="module-editor-row">{{ __('admin/builder.modules_content') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.modules_set_page') }}</div>
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

            <div class="item-group-wrapper" v-loading="loading">
              <template v-if="itemsData.length">
                <draggable
                  ghost-class="dragabble-ghost"
                  :list="itemsData"
                  @change="itemChange"
                  :options="{animation: 330}"
                >
                  <div v-for="(item, index) in itemsData" :key="index" class="item">
                    <div>
                      <i class="el-icon-s-unfold"></i>
                      <span>${item.name}</span>
                    </div>
                    <i class="el-icon-delete right" @click="removeProduct(index)"></i>
                  </div>
                </draggable>
              </template>
              <template v-else>{{ __('admin/builder.modules_please_pages') }}</template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-page', {
  delimiters: ['${', '}'],
  template: '#module-editor-page-template',
  props: ['module'],
  data: function () {
    return {
      keyword: '',
      itemsData: [],
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

      if (!this.form.items.length) return;
      this.loading = true;

      $http.get('pages/names?page_ids='+this.form.items.join(','), {hload: true}).then((res) => {
        this.loading = false;
        that.itemsData = res.data;
      })
    },

    querySearch(keyword, cb) {
      $http.get('pages/autocomplete?name=' + encodeURIComponent(keyword), null, {hload:true}).then((res) => {
        cb(res.data);
      })
    },

    handleSelect(item) {
      if (!this.form.items.find(v => v == item.id)) {
        this.form.items.push(item.id * 1);
        this.itemsData.push(item);
      }
      this.keyword = ""
    },

    itemChange(evt) {
      this.form.items = this.itemsData.map(e => e.id * 1);
    },

    removeProduct(index) {
      this.itemsData.splice(index, 1)
      this.form.items.splice(index, 1);
    },
  }
});
</script>

@push('footer-script')
  <script>
    register = @json($register);

    register.make = {
      style: {
        background_color: ''
      },
      floor: languagesFill(''),
      module_size: 'container-fluid',// 窄屏、宽屏、全屏
      items: [],
      title: languagesFill('{{ __('admin/builder.text_module_title') }}'),
    };

    app.source.modules.push(register)
  </script>
@endpush
