@extends('admin::layouts.master')

@section('title', '插件编辑')

@push('header')
  <script src="{{ asset('vendor/tinymce/5.9.1/tinymce.min.js') }}"></script>
  {{-- <script src="{{ asset('vendor/tinymce-vue/tinymce-vue.min.js') }}"></script> --}}
@endpush

@section('content')
  <div id="plugins-app-form" class="card">
    <div class="card-body pt-5">
      <el-form :model="form" :rules="rules" ref="form" label-width="110px">
        <div v-for="column, index in source.columns">
          <el-form-item :label="column.label" v-if="column.type == 'string'" class="form-max-w">
            <el-input v-model="form[column.name]" :placeholder="column.label"></el-input>
          </el-form-item>

          <el-form-item :label="column.label" v-if="column.type == 'text'" style="max-width: 900px;">
            <textarea v-model="form[column.name]" id="input-tinymce"></textarea>
          </el-form-item>

          <el-form-item :label="column.label" v-if="column.type == 'select'" class="form-max-w">
            <el-select v-model="form[column.name]" placeholder="请选择" >
              <el-option v-for="option, o_i in column.option" :key="o_i" :label="option"
                :value="option">
              </el-option>
            </el-select>
          </el-form-item>
        </div>
        <el-form-item label="状态" prop="status">
          <el-switch v-model="form.status"></el-switch>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submitForm('form')">提交</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#plugins-app-form',
      data: {
        customerTab: 'customer',
        form: {
          status: false
        },
        source: {
          columns: @json($plugin->getColumns())
        },

        rules: {
          // name: [{required: true, message: '请输入用户名', trigger: 'blur'}, ],
        },
      },

      // 在实例创建完成后被立即同步调用
      created () {
      },
      // 在挂载开始之前被调用:相关的 render 函数首次被调用
      beforeMount () {
        this.source.columns.forEach((e) => {
          this.$set(this.form, e.name, e.value)
        })
      },
      // 实例被挂载后调用
      mounted () {

      },

      methods: {
        submitForm(form) {
          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            $.ajax({
              url: `/admin/plugins/{{ $plugin->code }}`,
              type: 'PUT',
              data: this.form,
              success: function(res) {
                console.log(res)
              }
            })
          });
        },
      }
    });
  </script>

  <script>
    tinymce.init({
      selector: '#input-tinymce',
      language: "zh_CN",
      branding: false,
      height: 400,
      plugins: "link lists fullscreen table hr wordcount image imagetools code",
      menubar: "",
      toolbar: "undo redo | toolbarImageButton | bold italic underline strikethrough | forecolor backcolor | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | formatpainter removeformat | charmap emoticons | preview | template link anchor table toolbarImageUrlButton | fullscreen code",
      // contextmenu: "link image imagetools table",
      toolbar_items_size: 'small',
      image_caption: true,
      // imagetools_toolbar: 'imageoptions',
      toolbar_mode: 'wrap',
      font_formats:
        "微软雅黑='Microsoft YaHei';黑体=黑体;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Georgia=georgia,palatino;Helvetica=helvetica;Times New Roman=times new roman,times;Verdana=verdana,geneva",
      fontsize_formats: "10px 12px 14px 18px 24px 36px",
      relative_urls : true,
      setup : function(ed) {
         // console.log(111)
      }
    });
  </script>
@endpush



