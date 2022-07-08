<template id="module-editor-slideshow-template">
  <div>
    <div><h6>slideshow-template</h6></div>
  </div>
</template>

<script type="text/javascript">

setTimeout(() => {
  app.source.modules.push(@json($register))
}, 0)

Vue.component('module-editor-slideshow', {
  template: '#module-editor-slideshow-template',

  props: ['module'],

  data: function () {
    return {
      //
    }
  },

  created: function () {
    console.log(this.module)
  },

  methods: {
    //
  }
});
</script>
