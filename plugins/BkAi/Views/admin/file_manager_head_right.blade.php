<el-button size="small" class="me-1 ai-btn" plain @click="openAi"><img src="{{ plugin_asset('bk_ai', 'image/icon.png') }}" class="img-fluid"></el-button>

@push('admin.file_manager.vue.method')
,
openAi() {
  folderCurrent = this.folderCurrent;
  fileManagerApp = this;
  $('#filemanager-wrap-app').addClass('d-none');
  $('.image-ai-working').removeClass('d-none');
}
@endpush

@push('admin.file_manager.footer')
<div class="image-ai-working d-none">
  <div id="image-ai-app">
    <div class="title d-flex justify-content-between mb-3 align-items-center bg-white py-2 px-5 position-relative">
      <div class=""><button class="btn btn-outline-secondary ai-back"><i class="bi bi-arrow-left"></i> 返回</button></div>
      <div class="fs-3 d-flex align-items-center">AI 图片生成 <div class="quota ms-2" style="font-size: 14px">(剩余额度：<span class="has-quota">@{{ hasQuota }}</span>)</div> <a class="fs-6 ms-2 d-none set-quota" href="{{ beike_url() }}/subscribe/bk_ai?domain={{ request()->getHost() }}" target="_blank">充值额度 <i class="bi bi-arrow-up-right-square"></i></a></div>
      <div>
        <el-button v-if="generate.length > 1" type="primary" plain size="small" @click="selectAiImg('')"><i class="bi bi-cloud-download"></i> 保存全部</el-button>
      </div>
    </div>

    <div class="pb-3 px-5">
      <div class="d-flex align-items-center justify-content-center ai-top mb-3">
        <div class="w-75" v-if="progress > 0 && progress < 100">
          <div class="progress" role="progressbar" aria-label="Animated striped example" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar progress-bar-striped progress-bar-animated" :style="'width: ' + progress + '%'"></div>
          </div>
          <div class="img-ing-now mt-2">图片生成中... @{{ progress }}%</div>
        </div>

        <div v-if="generate.length">
          <div class="generate-result">
            <div v-for="(item, index) in generate" :key="index" class="item">
              <div class="img">
                <img :src="item" alt="" class="img-fluid">
              </div>
              <div class="img-tool d-flex justify-content-between align-items-center">
                <el-tooltip effect="dark" content="放大查看" placement="bottom">
                  <div @click="magnifyAiImg(index)"><i class="bi bi-arrows-fullscreen"></i></div>
                </el-tooltip>
                <el-tooltip effect="dark" content="保存到文件管理器" placement="bottom">
                  <div @click="selectAiImg(index)"><i class="bi bi-cloud-download"></i></div>
                </el-tooltip>
              </div>
            </div>
          </div>
        </div>
        <div v-if="progress <= 0 && !generate.length" class="generate-null rounded-3 fs-4">
          <div class="text-center text-secondary">图片生产区，请在下面指令框内输入生成指令 <i class="bi bi-arrow-down"></i></div>
        </div>
      </div>

      <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="failed_text">
        @{{ failed_text }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

      <div class="w-max-600">
        <el-form ref="ai-form" :rules="aiRules" :model="aiForm">
          <el-form-item label="请输入ai指令" prop="prompt" class="mb-3">
            <el-input v-model="aiForm.prompt" type="textarea" :rows="4" size="small" placeholder="prompt"></el-input>
          </el-form-item>
          <div class="d-flex styles-wrap">
            <el-form-item label="风格" prop="style" class="d-flex">
              <el-select v-model="aiForm.style" placeholder="请选择风格" size="small">
                <el-option v-for="item in styles" :key="item.value" :label="item.label" :value="item.value"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="尺寸" prop="size" class="ms-3 d-flex">
              <el-select v-model="aiForm.size" placeholder="请选择尺寸" size="small">
                <el-option v-for="item in sizes" :key="item" :label="item" :value="item"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="数量" prop="n" class="ms-3 d-flex">
              <el-select v-model="aiForm.n" placeholder="请选择数量" size="small">
                <el-option v-for="item in ns" :key="item" :label="item" :value="item"></el-option>
              </el-select>
            </el-form-item>
          </div>
          <el-form-item class="mb-0">
            <el-button :loading="loading" type="primary" @click="aiGenerate('ai-form')">
              <span v-if="!loading">生成图片</span>
              <span v-else>生成中，大概需要 20 秒左右</span>
            </el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>

  <script>
    const API = {
      generateImage: "{{ admin_route('plugin.mj_ai_image.generate_image') }}",
      checkImage: "{{ admin_route('plugin.mj_ai_image.check_image') }}",
      saveImage: "{{ admin_route('plugin.mj_ai_image.save_image') }}",
    }

    var folderCurrent = '';
    var fileManagerApp = null;
    let intervalId = null;

    $(document).on('click', '.ai-back', function() {
      $('#filemanager-wrap-app').removeClass('d-none');
      $('.image-ai-working').addClass('d-none');
    });

    let imageAiApp = new Vue({
      el: '#image-ai-app',

      data: {
        task_id: 0,
        hasQuota: '',
        progress: 0,
        // generate: [
        //   "https://cdnb.ttapi.io/20241021/7895d98e-e846-4533-aa76-ab726d2d651etl.png",
        //   "https://cdnb.ttapi.io/20241021/7895d98e-e846-4533-aa76-ab726d2d651etr.png",
        //   "https://cdnb.ttapi.io/20241021/7895d98e-e846-4533-aa76-ab726d2d651ebl.png",
        //   "https://cdnb.ttapi.io/20241021/7895d98e-e846-4533-aa76-ab726d2d651ebr.png"
        // ],
        generate: [],

        styles: [
          { label: '默认值，由模型随机输出图像风格', value: '<auto>' },
          { label: '摄影', value: '<photography>' },
          { label: '人像写真', value: '<portrait>' },
          { label: '3D卡通', value: '<3d cartoon>' },
          { label: '动画', value: '<anime>' },
          { label: '油画', value: '<oil painting>' },
          { label: '水彩', value: '<watercolor>' },
          { label: '素描', value: '<sketch>' },
          { label: '中国画', value: '<chinese painting>' },
          { label: '扁平插画', value: '<flat illustration>' },
        ],

        sizes: ['1024*1024', '720*1280', '768*1152', '1280*720'],

        ns: [1,2,3,4],

        failed_text: '',

        aiForm: {
          prompt: '',
          style: '<auto>',
          size: '1024*1024',
          n: 1,
        },

        aiRules: {
          prompt: [
            { required: true, message: '请输入ai指令', trigger: 'blur' },
          ],
        },

        loading: false,

        source: {
        },
      },

      mounted() {
        this.getQuota()
      },

      methods: {
        aiGenerate(form) {
          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{ __('common.error_form') }}');
              return;
            }

            let prompt = this.aiForm.prompt;

            if (!prompt) {
              layer.msg('请输入指令', ()=>{});
              return;
            }

            this.loading = true;

            $http.post(API.generateImage, this.aiForm, {hload: true}).then((res) => {
              let data = JSON.parse(res);
              console.log(data);
              if (data.data.status == "fail") {
                this.failed_text = data.msg;
                this.loading = false;
                return;
              }
              this.failed_text = '';
              if (data.data.output.task_status == 'PENDING') {
                this.task_id = data.data.output.task_id;
                this.hasQuota = data.data.has_quota;
                this.startPolling();

                if (data.data.has_quota < 10) {
                  $('.set-quota').removeClass('d-none');
                }
              }
            })
          });
        },

        startPolling() {
          $http.get(API.checkImage, {task_id: this.task_id}, {hload: true}).then((res) => {
            let data = JSON.parse(res);
            console.log(data);
            if (data.output.task_status == 'FAILED') {
              this.failed_text = data.output.message;
              clearInterval(intervalId);
              this.loading = false;
              return;
            }

            if (data.output.task_status == 'SUCCEEDED' || data.output.task_status == 'UNKNOWN') {
              this.generate = data.output.results.map(e => e.url);
              clearInterval(intervalId);
              this.loading = false;
              return;
            }

            intervalId = setTimeout(() => this.startPolling(), 2000);
          })
        },

        selectAiImg(index) {
          let urls = [];
          if (index == '') {
            urls = this.generate
          } else {
            urls[0] = this.generate[index];
          }

          $http.post(API.saveImage, {urls: urls, path: folderCurrent}).then((res) => {
            if (res.status == 'success') {
              this.$message.success('保存成功');
              fileManagerApp.loadData()
            }
          })
        },

        magnifyAiImg(index) {
          let images = this.generate.map((item) => {
            return {
              src: item,
              thumb: item,
              subHtml: '<h4>AI 生成图片</h4>'
            }
          });

          layer.photos({
            photos: {
              "title": "AI 生成图片",
              "id": 1,
              "start": index,
              "data": images
            },
            anim: 5
          });
        },

        getQuota() {
          $http.get('{{ admin_route('plugin.bk_ai.get_quota') }}', null, {hload: true}).then((res) => {
            let data = JSON.parse(res);
            this.hasQuota = data.data;
            if (data.data < 10) {
              $('.set-quota').removeClass('d-none');
            }
          })
        }
      }
    })
  </script>

  <style>
    .ai-top {
      min-height: 180px;
      padding: 20px 10px;
      border-radius: 6px;
      background-color: #fff;
      border: 1px solid #e9ecef;
    }

    .generate-result {
      display: flex;
      flex-wrap: wrap;
    }

    .generate-result .item {
      width: 180px;
      margin-right: 10px;
    }

    .generate-result .item .img {
      width: 180px;
      height: 180px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1px solid #e9ecef;
    }

    .generate-result .item .img img {
      max-height: 100%;
    }

    .generate-result .img-tool > div {
      /* margin-right: 10px; */
      padding: 5px;
      background-color: #f8f9fa;
      border-right: 1px solid #eee;
      border-bottom: 1px solid #eee;
      text-align: center;
      width: 50%;
      cursor: pointer;
    }

    .generate-result .img-tool > div:first-child {
      border-left: 1px solid #eee;
    }

    .generate-result .img-tool > div:hover {
      background-color: #e9ecef;
    }

    .content-head .right {
      display: flex;
      align-items: center;
    }

    .ai-btn {
      font-size: 18px;
      height: 33px;
      width: 34px;
      text-align: center;
      padding: 0 !important;
      color: #FF5722;
      border: none;
      overflow: hidden;
    }

    .ai-btn:hover {
      color: #fff !important;
      border-color: #FF5722 !important;
      background-color: #FF5722 !important;
    }
  </style>
</div>
@endpush