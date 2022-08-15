<template id="text-i18n-template">
  <div class="text-i18n-template">
    <el-tabs v-if="languages.length > 1" value="language-{{ locale() }}"
      :stretch="languages.length > 5 ? true : false" type="card">
      <el-tab-pane v-for="(item, index) in languages" :key="index" :label="item.name"
        :name="'language-' + item.code">
        <span slot="label" style="padding: 0 8px; font-size: 12px">@{{ item.name }}</span>

        <div class="i18n-inner">
          <el-input :type="type" :rows="4" :placeholder="item.name" :key="index"
            :size="size" v-model="value[item.code]" @input="(val) => {valueChanged(val, item.code)}">
          </el-input>
        </div>
      </el-tab-pane>
    </el-tabs>

    <div class="i18n-inner" v-else>
      <el-input :type="type" :rows="4" :placeholder="languages[0].name" :size="size"
        :value="value[languages[0].code]" @input="(val) => {valueChanged(val, languages[0].code)}"></el-input>
    </div>
  </div>
</template>

<script type="text/javascript">
  Vue.component('text-i18n', {
    template: '#text-i18n-template',
    props: {
      value: {
        default: null
      },
      size: {
        default: 'small'
      },
      type: {
        type: String,
        default: 'text'
      },
    },
    data: function() {
      return {
        languages: $languages,
        internalValues: {}
      }
    },

    created: function() {
      this.initData()
    },

    methods: {
      valueChanged(val, code) {
        this.internalValues[code] = val;
        // this.$emit('input', JSON.parse(JSON.stringify(this.internalValues)));
        this.$emit('input', this.internalValues);
      },

      initData() {
        this.languages.forEach(e => {
          Vue.set(this.internalValues, e.code, this.value[e.code] || '');
        })
        // this.$emit('input', JSON.parse(JSON.stringify(this.internalValues)));
        this.$emit('input', this.internalValues);
      }
    }
  });
</script>

<style>
  .text-i18n-template .el-tabs__nav {
    display: flex;
    border-color: #ebecf5;
  }

  .text-i18n-template .el-tabs__nav>div {
    background: #ebecf5;
    border-left: 1px solid #d7dbf7 !important;
    padding: 0 !important;
    flex: 1;
    height: 30px;
    line-height: 30px;
    text-align: center;
  }

  .text-i18n-template .el-tabs__nav>div:first-of-type {
    border-left: none !important;
  }

  .text-i18n-template .el-tabs__nav>div.is-active {
    background: #fff !important;
  }

  .text-i18n-template .i18n-inner {
    margin-top: 5px;
  }

  .text-i18n-template .el-tabs__header {
    margin-bottom: 0;
  }
</style>
