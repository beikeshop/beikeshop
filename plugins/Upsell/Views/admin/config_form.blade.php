@section('page-title-right')
<button type="button" class="btn btn-primary save-btn" onclick="app.submit('form')">{{ __('common.save') }}</button>
@endsection
<div class="mb-5" id="app">
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">id</th>
        <th scope="col">type</th>
        <th scope="col">subtype</th>
        <th scope="col">target</th>
        <th scope="col">related</th>
        <th scope="col">priority</th>
        <th scope="col">price</th>
      </tr>
    </thead>
    <tbody>

      @foreach ($activities as $index => $activity)
      <tr>
      <td scope="row">{{ $index + 1 }}</td>
      <td>{{ $activity->id }}</td>
      <td>{{ $activity->type }}</td>
      <td>{{ $activity->subtype }}</td>
      <td>{{ $activity->target }}</td>
      <td>{{ $activity->related }}</td>
      <td>{{ $activity->priority }}</td>
      <td>{{ $activity->price }}</td>
      </tr>
    @endforeach

    </tbody>
  </table>
  {{-- elementui table--}}
  <el-table ref="filterTable" :data="tableData" style="width: 100%">
    <el-table-column type="index" label="#"></el-table-column>
    <el-table-column prop="type" label="type" sortable width="180">
    </el-table-column>
    <el-table-column prop="subtype" label="subtype" width="180">
    </el-table-column>
    <el-table-column prop="target" label="target"> </el-table-column>
    <el-table-column prop="related" label="related"></el-table-column>
    <el-table-column prop="priority" label="priority"></el-table-column>
    <el-table-column prop="price" label="price"></el-table-column>
  </el-table>
</div>
<!-- <script src="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.js"></script> -->
<script>
  let app = new Vue({
    el: '#app',
    data() {
      return {
        tableData: @json($activities)
      }
    },
    methods: {
      resetDateFilter() {
        this.$refs.filterTable.clearFilter('date');
      },
      clearFilter() {
        this.$refs.filterTable.clearFilter();
      },
      formatter(row, column) {
        return row.address;
      },
      filterTag(value, row) {
        return row.tag === value;
      },
      filterHandler(value, row, column) {
        const property = column['property'];
        return row[property] === value;
      },
      submit(form) {
        alert(`form submit ${this.plugin.code}`)
      }
    }
  })
</script>