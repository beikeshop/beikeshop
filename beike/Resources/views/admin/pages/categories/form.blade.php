@extends('beike::admin.layouts.master')

@section('title', '分类管理')

@section('content')
  <div id="category-app" class="card">
    <div class="card-header">
      所有分类
    </div>
    <div class="card-body">
      <form action="{{ admin_route($category->id ? 'categories.update' : 'categories.store', $category) }}" method="POST">
        @csrf
        @method($category->id ? 'PUT' : 'POST')
        <input type="hidden" name="_redirect" value="{{ request()->header('referer') ?? admin_route('categories.index') }}">

        @foreach (locales() as $index => $locale)
          <input type="hidden" name="descriptions[{{ $index }}][locale]" value="{{ $locale['code'] }}">
        @endforeach

        @foreach (locales() as $index => $locale)
          <input type="text" name="descriptions[{{ $index }}][name]" placeholder="Name {{ $locale['name'] }}" value="{{ old('descriptions.'.$index.'.name', $descriptions[$locale['code']]->name ?? '') }}">
          @error('descriptions.'.$index.'.name')
            <x-beike::form.error :message="$message" />
          @enderror
          <input type="text" name="descriptions[{{ $index }}][content]" placeholder="content {{ $locale['name'] }}" value="{{ old('descriptions.'.$index.'.content', $descriptions[$locale['code']]->content ?? '') }}">
          <hr>
        @endforeach

        <input type="text" name="parent_id" value="{{ old('parent_id', $category->parent_id ?? 0) }}" placeholder="上级分类">
        <input type="text" name="active" value="{{ old('active', $category->active ?? 1) }}" placeholder="状态">

        <button type="submit" class="btn btn-primary">保存</button>
      </form>

    </div>
  </div>
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#category-app',
      data() {
        return {
          data: [{
            label: '一级 1',
            children: [{
              label: '二级 1-1',
              children: [{
                label: '三级 1-1-1'
              }]
            }]
          }, {
            label: '一级 2',
            children: [{
              label: '二级 2-1',
              children: [{
                label: '三级 2-1-1'
              }]
            }, {
              label: '二级 2-2',
              children: [{
                label: '三级 2-2-1'
              }]
            }]
          }, {
            label: '一级 3',
            children: [{
              label: '二级 3-1',
              children: [{
                label: '三级 3-1-1'
              }]
            }, {
              label: '二级 3-2',
              children: [{
                label: '三级 3-2-1'
              }]
            }]
          }],
          defaultProps: {
            children: 'children',
            label: 'label'
          }
        };
      },

      methods: {
        handleNodeClick(data) {
          console.log(data);
        }
      }
    });
</script>
@endpush
