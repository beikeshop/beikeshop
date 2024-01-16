<template id="module-editor-brand-template">
  <div class="image-edit-wrapper">
    <div class="module-editor-row">{{ __('admin/builder.text_set_up') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_module_title') }}</div>
      <text-i18n v-model="form.title"></text-i18n>
    </div>

    <div class="module-editor-row">{{ __('admin/builder.modules_content') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.modules_choose_brand') }}</div>

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
      form: null,
      keyword: '',
    }
  },

  watch: {
    form: {
      handler: function (val) {
        this.$emit('on-changed', val);
      },
      deep: true
    }
  },

  created: function () {
    this.form = JSON.parse(JSON.stringify(this.module));
    if (!this.form.brands.length) return;

    const ids = this.form.brands.join(',');
    this.loading = true;
    $http.get(`brands/names?ids=${ids}`, null, {hload:true}).then((res) => {
      this.brands = res.data
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
      if (!this.form.brands.find(v => v == item.id)) {
        this.form.brands.push(item.id * 1);
        this.brands.push(item);
      }
      this.keyword = ""
    },

    removeProduct(index) {
      this.brands.splice(index, 1)
      this.form.brands.splice(index, 1);
    },

    itemChange(evt) {
      this.form.brands = this.brands.map(e => e.id * 1);
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
      full: true,
      title: languagesFill('{{ __('admin/builder.text_module_title') }}'),
      brands: []
    }

    app.source.modules.push(register)
  </script>
@endpush
