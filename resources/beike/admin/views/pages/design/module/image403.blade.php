<template id="module-editor-image403-template">
  <div class="image-edit-wrapper">
    <module-size v-model="form.module_size"></module-size>

    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_module_title') }}</div>
      <text-i18n v-model="form.title"></text-i18n>
    </div>

    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.sub_title') }}</div>
      <text-i18n v-model="form.sub_title"></text-i18n>
    </div>

    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_add_pictures') }}</div>
      <div class="pb-images-selector" v-for="(item, index) in form.images" :key="index">
        <div class="selector-head" @click="itemShow(index)">
          <div class="left">
            <img :src="thumbnail(item.image.src, 40, 40)" class="img-responsive">
          </div>

          <div class="right"><i :class="'el-icon-arrow-'+(item.show ? 'up' : 'down')"></i></div>
        </div>
        <div :class="'pb-images-list ' + (item.show ? 'active' : '')">
          <div class="pb-images-top">
            <pb-image-selector :is-alt="true"  v-model="item.image" :is-language="false"></pb-image-selector>
            <div class="tag">{{ __('admin/builder.text_suggested_size') }}:
              <span>560 x 730</span>
            </div>
          </div>
          <link-selector v-model="item.link" style="margin-bottom: 10px"></link-selector>
          <div class="module-edit-title">{{ __('admin/builder.text_title') }}</div>
          <text-i18n v-model="item.title" style="margin-bottom: 10px"></text-i18n>

          <div class="module-edit-title">{{ __('admin/builder.sub_title') }}</div>
          <text-i18n v-model="item.sub_title" style="margin-bottom: 10px"></text-i18n>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-image403', {
  template: '#module-editor-image403-template',

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
      module_size: 'container-fluid',// 窄屏、宽屏、全屏
      floor: languagesFill(''),
      title: languagesFill('{{ __('admin/builder.text_module_title') }}'),
      sub_title: languagesFill('{{ __('admin/builder.sub_title') }}'),
      images: [
        {
          image: {
            src: 'catalog/demo/banner/banner-403-1.jpg',
            alt: languagesFill(''),
          },
          sub_title: languagesFill(''),
          title: languagesFill(''),
          show: true,
          link: {
            type: 'product',
            value:''
          }
        },
        {
          image: {
            src: 'catalog/demo/banner/banner-403-2.jpg',
            alt: languagesFill(''),
          },
          sub_title: languagesFill(''),
          title: languagesFill(''),
          show: false,
          link: {
            type: 'product',
            value:''
          }
        },
        {
          image: {
            src: 'catalog/demo/banner/banner-403-3.jpg',
            alt: languagesFill(''),
          },
          sub_title: languagesFill(''),
          title: languagesFill(''),
          show: false,
          link: {
            type: 'product',
            value:''
          }
        },
        {
          image: {
            src: 'catalog/demo/banner/banner-403-4.jpg',
            alt: languagesFill(''),
          },
          sub_title: languagesFill(''),
          title: languagesFill(''),
          show: false,
          link: {
            type: 'product',
            value:''
          }
        },
      ]
    }

    app.source.modules.push(register)
  </script>
@endpush