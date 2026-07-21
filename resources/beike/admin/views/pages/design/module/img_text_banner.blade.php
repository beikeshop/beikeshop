<template id="module-editor-img-text-banner-template">
  <div>
    <div class="module-edit-group">
      <module-size v-model="module.module_size"></module-size>

      <div class="module-edit-title">{{ __('admin/builder.modules_select_image') }}</div>
      <div class="pb-images-top">
        <pb-image-selector :is-alt="true"  v-model="module.image" :is-language="false"></pb-image-selector>
        <div class="tag">{{ __('admin/builder.text_suggested_size') }} 1000 x 600</div>
      </div>
      <link-selector v-model="module.link" style="margin-bottom: 10px"></link-selector>

      <div class="module-edit-title">{{ __('admin/builder.image_position') }}</div>
      <el-radio-group v-model="module.image_position" size="mini" class="mb-1">
        <el-radio-button label="left">{{ __('admin/builder.text_start') }}</el-radio-button>
        <el-radio-button label="right">{{ __('admin/builder.text_end') }}</el-radio-button>
      </el-radio-group>

      <div class="module-edit-title">{{ __('admin/builder.text_title') }}</div>
      <text-i18n v-model="module.title" style="margin-bottom: 10px"></text-i18n>

      <div class="module-edit-title">{{ __('admin/builder.sub_title') }}</div>
      <text-i18n v-model="module.sub_title" style="margin-bottom: 10px"></text-i18n>

      <div class="module-edit-title">{{ __('admin/builder.text_describe') }}</div>
      <text-i18n v-model="module.description" style="margin-bottom: 10px"></text-i18n>

      <div class="module-edit-title">{{ __('admin/builder.text_position') }}</div>
      <el-radio-group v-model="module.text_position" size="mini" class="mb-1">
        <el-radio-button label="left">{{ __('admin/builder.text_start') }}</el-radio-button>
        <el-radio-button label="center">{{ __('admin/builder.text_center') }}</el-radio-button>
        <el-radio-button label="right">{{ __('admin/builder.text_end') }}</el-radio-button>
      </el-radio-group>

      <div class="module-edit-title">{{ __('admin/builder.text_max_width') }}</div>
      <el-input v-model="module.text_max_width" :min="1" size="mini" class="mb-1">
        <template #append>px</template>
      </el-input>

      <div>
        <div style="margin-right: 20px;">
          <div class="module-edit-title">{{ __('admin/builder.scroll_text_bg') }}</div>
          <el-color-picker v-model="module.bg_color" size="small" class="mb-1"></el-color-picker>
        </div>
        <div>
          <div class="module-edit-title">{{ __('admin/builder.text_font_color') }}</div>
          <el-color-picker v-model="module.text_color" size="small" class="mb-1"></el-color-picker>
        </div>
      </div>

      <div>
        <div style="margin-right: 20px;">
          <div class="module-edit-title">{{ __('admin/builder.btn_bg') }}</div>
          <el-color-picker v-model="module.btn_bg" size="small" class="mb-1"></el-color-picker>
        </div>
        <div>
          <div class="module-edit-title">{{ __('admin/builder.btn_color') }}</div>
          <el-color-picker v-model="module.btn_color" size="small" class="mb-1"></el-color-picker>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">

Vue.component('module-editor-img-text-banner', {
  template: '#module-editor-img-text-banner-template',

  props: ['module'],

  data: function () {
    return {
      // full: true
    }
  },

  watch: {
    module: {
      handler: function (val) {
        this.$emit('on-changed', val);
      },
      deep: true,
    }
  },

  methods: {

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
      module_size: 'container-fluid',// 窄屏、宽屏、全屏
      bg_color: '#F7F6F1',
      text_color: '#222222',
      btn_bg: '#fd560f',
      btn_color: '#ffffff',
      image_position: 'right',
      text_position: 'left',
      text_max_width: '',
      image: 'https://dummyimage.com/1000x600/eeeeee',
      title: languagesFill(''),
      sub_title: languagesFill(''),
      description: languagesFill(''),
      link: {
        type: 'product',
        value:''
      }
    }

    app.source.modules.push(register)
  </script>
@endpush