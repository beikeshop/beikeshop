<template id="module-editor-icons-template">
  <div class="image-edit-wrapper">
    <div class="module-editor-row">{{ __('admin/builder.text_set_up') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_module_title') }}</div>
      <text-i18n v-model="form.title"></text-i18n>
    </div>
    <div class="module-edit-group" style="margin-bottom: 200px;">
      <div class="module-edit-title">{{ __('admin/builder.text_add_pictures') }}</div>
      <div class="pb-images-selector" v-for="(item, index) in form.images" :key="index">
        <div class="selector-head" @click="itemShow(index)">
          <div class="left">

            <img :src="thumbnail(item.image, 40, 40)" class="img-responsive">
          </div>

          <div class="right">
            <span @click="removeItem(index)" class="remove-item"><i class="el-icon-delete"></i></span>
            <i :class="'el-icon-arrow-'+(item.show ? 'up' : 'down')"></i>
          </div>
        </div>
        <div :class="'pb-images-list ' + (item.show ? 'active' : '')">
          <div class="pb-images-top">
            <pb-image-selector v-model="item.image" :is-language="false"></pb-image-selector>
            <div class="tag">{{ __('admin/builder.text_suggested_size') }}: 200x200</div>
          </div>
          <div class="module-edit-title">{{ __('admin/builder.text_set_title') }}</div>
          <text-i18n v-model="item.text" style="margin-bottom: 10px"></text-i18n>
          <div class="module-edit-title">{{ __('admin/builder.text_set_subtitle') }}</div>
          <text-i18n v-model="item.sub_text" style="margin-bottom: 10px"></text-i18n>
          <link-selector v-model="item.link"></link-selector>
        </div>
      </div>
      <div class="add-items" style="margin-top: 20px">
        <el-button icon="el-icon-circle-plus-outline" type="primary" size="small" style="width: 100%" @click="addItems" plain>{{ __('common.add') }}</el-button>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-icons', {
  template: '#module-editor-icons-template',

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

    addItems() {
      this.form.images.push({
        image: '',
        link: {
          type: 'product',
          value:''
        },
        text: languagesFill(''),
        sub_text: languagesFill(''),
        show: true
      })

      this.form.images.find((e, key) => {if (this.form.images.length - 1 != key) return e.show = false});
    },

    removeItem(index) {
      this.form.images.splice(index, 1);
    }
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
      title: languagesFill('{{ __('admin/builder.text_module_title') }}'),
      floor: languagesFill(''),
      images: []
    }

    app.source.modules.push(register)
  </script>
@endpush