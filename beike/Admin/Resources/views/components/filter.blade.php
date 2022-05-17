<div id="filter-app">
  <form class="form-inline">
    <input type="text" v-model="keyword" class="form-control mr-2">
    <button type="button" @click="search" class="btn btn-primary">筛选</button>
  </form>
  
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
