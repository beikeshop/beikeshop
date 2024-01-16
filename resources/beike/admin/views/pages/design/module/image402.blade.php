<template id="module-editor-image402-template">
  <div class="image-edit-wrapper">
    <div class="module-editor-row">{{ __('admin/builder.text_set_up') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_module_title') }}</div>
      <text-i18n v-model="form.title"></text-i18n>
    </div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_set_subtitle') }}</div>
      <text-i18n v-model="form.sub_title"></text-i18n>
    </div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_add_pictures') }}</div>
      <div class="pb-images-selector" v-for="(item, index) in form.images" :key="index">
        <div class="selector-head" @click="itemShow(index)">
          <div class="left">
            <img :src="thumbnail(item.image, 40, 40)" class="img-responsive">
          </div>
          <div class="right"><i :class="'el-icon-arrow-'+(item.show ? 'up' : 'down')"></i></div>
        </div>
        <div :class="'pb-images-list ' + (item.show ? 'active' : '')">
          <div class="pb-images-top">
            <pb-image-selector :is-language="false" v-model="item.image"></pb-image-selector>
            <div class="tag">{{ __('admin/builder.text_suggested_size') }}:
              <span v-if="index == 0 || index == 3">500 x 700</span>
              <span v-if="index == 1 || index == 2">500 x 338</span>
            </div>
          </div>
          <div class="module-edit-title">{{ __('admin/builder.text_word') }}</div>
          <text-i18n v-model="item.title" style="margin-bottom: 10px"></text-i18n>
          <link-selector v-model="item.link"></link-selector>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-image402', {
  template: '#module-editor-image402-template',

  props: ['module'],

  data: function () {
    return {
      form: null
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
  },

  methods: {
    itemShow(index) {
      this.form.images.find((e, key) => {if (index != key) return e.show = false});
      this.form.images[index].show = !this.form.images[index].show;
    },
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
      floor: languagesFill(''),
      title: languagesFill('{{ __('admin/builder.text_set_title') }}'),
      sub_title: languagesFill('{{ __('admin/builder.text_set_subtitle') }}'),
      images: [
        {
          image: 'catalog/demo/banner/banner-402-1.jpg',
          show: true,
          title: languagesFill('{{ __('admin/builder.text_word') }}'),
          link: {
            type: 'product',
            value:''
          }
        },
        {
          image: 'catalog/demo/banner/banner-402-2.jpg',
          show: false,
          title: languagesFill('{{ __('admin/builder.text_word') }}'),
          link: {
            type: 'product',
            value:''
          }
        },
        {
          image: 'catalog/demo/banner/banner-402-3.jpg',
          show: false,
          title: languagesFill('{{ __('admin/builder.text_word') }}'),
          link: {
            type: 'product',
            value:''
          }
        },
        {
          image: 'catalog/demo/banner/banner-402-4.jpg',
          show: false,
          title: languagesFill('{{ __('admin/builder.text_word') }}'),
          link: {
            type: 'product',
            value:''
          }
        }
      ]
    }

    app.source.modules.push(register)
  </script>
@endpush