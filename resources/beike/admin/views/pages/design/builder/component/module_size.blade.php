<template id="module-size-template">
  <div class="module-size-template">
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.module_size') }}</div>
      <el-radio-group v-model="localValue" size="mini">
        <el-radio-button label="container">{{ __('admin/builder.module_size_narrow') }}</el-radio-button>
        <el-radio-button label="container-fluid">{{ __('admin/builder.module_size_wide') }}</el-radio-button>
        <el-radio-button label="w-100">{{ __('admin/builder.module_size_full') }}</el-radio-button>
      </el-radio-group>
    </div>
  </div>
</template>

<script type="text/javascript">
  Vue.component('module-size', {
    template: '#module-size-template',
    props: {
      value: {
        type: String,
        default: 'container-fluid'
      },
    },
    data() {
      return {
        localValue: this.value,
      };
    },
    watch: {
      localValue(newVal) {
        this.$emit('input', newVal);
      },
    },
    methods: {
    }
  });
</script>
