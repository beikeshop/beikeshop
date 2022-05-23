@extends('admin::layouts.master')

@section('content')
    <div id="app">
        <div class="card">
            <div class="card-body">
                <el-upload
                        class="upload-demo"
                        drag
                        :headers="headers"
                        action="{{ admin_route('files.store') }}"
                        multiple
                        with-credentials
                >
                    <i class="el-icon-upload"></i>
                    <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                    <div class="el-upload__tip" slot="tip">只能上传jpg/png文件，且不超过500kb</div>
                </el-upload>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script>
    new Vue({
      el: '#app',
      data: {
        files: [],
        headers: {
          'X-CSRF-TOKEN': @json(csrf_token())
        }
      },
      methods: {
      }
    })


    </script>
@endpush
