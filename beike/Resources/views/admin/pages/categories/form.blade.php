@extends('beike::admin.layouts.master')

@section('title', '分类管理')

@section('content')
  <div id="category-app" class="card">
    <div class="card-header">
      编辑分类
    </div>
    <div class="card-body">
      <form action="{{ admin_route($category->id ? 'categories.update' : 'categories.store', $category) }}" method="POST">
        @csrf
        @method($category->id ? 'PUT' : 'POST')
        <input type="hidden" name="_redirect" value="{{ $_redirect }}">

        @foreach (locales() as $index => $locale)
          <input type="hidden" name="descriptions[{{ $index }}][locale]" value="{{ $locale['code'] }}">
        @endforeach

        <x-beike-form-input-locale name="descriptions.*.name" title="名称" :value="$descriptions" required />
        <x-beike-form-input-locale name="descriptions.*.content" title="内容" :value="$descriptions" />

        <x-beike::form.row title="上级分类">
          @php
            $_parent_id = old('parent_id', $category->parent_id ?? 0);
          @endphp
          <select name="parent_id" id="" class="form-control form-control-sm short">
            <option value="0">--请选择--</option>
            @foreach ($categories as $_category)
              <option value="{{ $_category->id }}" {{ $_parent_id == $_category->id ? 'selected' : ''}}>
                {{ $_category->name }}
              </option>
            @endforeach
          </select>
        </x-beike::form.row>

        <x-beike-form-switch title="状态" name="active" :value="old('active', $category->active ?? 1)" />

        <div>
          <button type="submit" class="btn btn-primary">保存</button>
          <a href="{{ $_redirect }}" class="btn btn-danger">返回</a>
        </div>
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
