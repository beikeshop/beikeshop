<div id="filter-app">
  <input type="text" v-model="keyword">
  <button type="button" @click="search">筛选</button>
</div>

@push('footer')
  <script>
    new Vue({
      el: '#filter-app',
      data: {
        keyword: ''
      },
      methods: {
        search() {
          let queries = @json($queries);
          let url = @json($url);

          if (this.keyword != '') {
            queries['keyword'] = this.keyword;
          }

          if (Object.keys(queries).length) {
            url = url + '?' + new URLSearchParams(queries).toString();
          }

          window.location = url;
        }
      }
    });
  </script>
@endpush
