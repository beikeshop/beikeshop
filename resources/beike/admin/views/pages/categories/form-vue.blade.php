@extends('admin::admin.layouts.master')

@section('title', '分类管理')

@section('content')
  <div id="app">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>编辑分类</span>
      </div>

      <el-form label-width="200px" size="small1">

        <el-form-item label="分类名称">
          <div style="max-width: 400px">
            @foreach (locales() as $locale)
              <el-input class="mb-1">
                <template slot="append">{{ $locale['name'] }}</template>
              </el-input>
            @endforeach
          </div>
        </el-form-item>

        <el-form-item label="分类描述">
          <div style="max-width: 400px">
            @foreach (locales() as $locale)
              <el-input v-model="form.descriptions['{{ $locale['code'] }}'].content" class="mb-1">
                <template slot="append">{{ $locale['name'] }}</template>
              </el-input>
            @endforeach
          </div>
        </el-form-item>

        <el-form-item label="上级分类">
          <el-select v-model="form.parent_id" placeholder="请选择上级分类">
            @foreach ($categories as $_category)
              <el-option label="{{ $_category->name }}" value="{{ $_category->id }}"></el-option>
            @endforeach
          </el-select>
        </el-form-item>

        <el-form-item label="状态">
          <el-radio-group v-model="form.active">
            <el-radio :label="1">启用</el-radio>
            <el-radio :label="0">禁用</el-radio>
          </el-radio-group>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="save">立即创建</el-button>
          <el-button>取消</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#app',
      data() {
        return {
          form: {
            parent_id: 0,
            active: 1,
            descriptions: {
              zh_cn: {
                name: '',
                content: '',
              },
              en: {
                name: '',
                content: '',
              }
            }
          }
        };
      },

      methods: {
        save() {
          axios.post(@json(admin_route('categories.store')), this.form).then(response => {
            this.loading = false;
          }).catch(error => {
            // this.$message.error(error.response.data.message);
          });
        }
      }
    });
  </script>
@endpush
