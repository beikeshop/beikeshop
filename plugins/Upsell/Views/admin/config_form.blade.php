@section('page-title-right')
<button type="button" class="btn btn-primary save-btn" onclick="app.submit('form')">{{ __('common.save') }}</button>
@endsection
<div class="mb-5" id="app">
  自定义的插件设置入口
  <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Handle</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td colspan="2">Larry the Bird</td>
      <td>@twitter</td>
    </tr>
  </tbody>
</table>
</div>
<script>
  let app = new Vue({
    el: '#app',
    data() {
      return {
        plugin: {
          code: "upsell"
        }
      }
    },
    computed: {
    },

    methods: {
      submit(form) {
        alert(`form submit ${this.plugin.code}`)
      }
    }
  })
</script>