<!DOCTYPE html>
<html lang="{{ locale() }}">

<head>
  <meta charset="UTF-8">
  <base href="{{ $admin_base_url }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="asset" content="{{ asset('/') }}">
  <script src="{{ asset('vendor/cookie/js.cookie.min.js') }}"></script>
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <link href="{{ mix('/build/beike/admin/css/bootstrap.css') }}" rel="stylesheet">
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ mix('build/beike/admin/js/app.js') }}"></script>
  <title>{{ __('BkAi::common.magic_ai') }}</title>
  <script>
    const lang = {
      file_manager: '{{ __('admin/file_manager.file_manager') }}',
      error_form: '{{ __('common.error_form') }}',
      text_hint: '{{ __('common.text_hint') }}',
      translate_form: '{{ __('admin/common.translate_form') }}',
      choose: '{{ __('common.choose') }}',
    }

    const config = {
      beike_version: '{{ config('beike.version') }}',
      api_url: '{{ beike_api_url() }}',
      app_url: '{{ config('app.url') }}',
    }
  </script>
</head>
<body class="page-bkai">
  <div class="container-fluid p-3">
    <div class="head-wrap d-flex justify-content-between align-items-center mb-2">
      <h5 class="title"></h5>
      <div class="quota">剩余额度：<span class="has-quota"></span> <a class="fs-6 ms-2 d-none set-quota" href="{{ beike_url() }}/subscribe/bk_ai?domain={{ request()->getHost() }}" target="_blank">充值额度 <i class="bi bi-arrow-up-right-square"></i></a></div>
    </div>
    <form class="row g-3 needs-validation magic-ai-form no-load" novalidate>
      @if (request('locale'))
      <div class="col-12">
        <label class="form-label">{{ __('BkAi::common.lang_select') }}</label>
        <select class="form-select" name="lang">
          <option value="all">{{ __('BkAi::common.lang_select_2') }}</option>
          <option value="current">{{ __('BkAi::common.lang_select_1') }}</option>
        </select>
      </div>
      @endif

      <div class="col-12">
        <label class="form-label">{{ __('BkAi::common.prompt') }}</label>
        <ul class="nav nav-tabs ai-input-tabs mb-2">
          <li class="nav-item">
            <button class="nav-link active" type="button" data-bs-toggle="tab" data-bs-target="#tabs-ai-line">简单模式</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" type="button" data-bs-toggle="tab" data-bs-target="#tabs-ai-pro">专业模式</button>
          </li>
        </ul>
        <div class="tab-content" id="tabs-tabContent">
          <div class="tab-pane fade show active" id="tabs-ai-line">
            <textarea class="form-control mb-1" rows="2" name="prompt_line" placeholder="{{ __('BkAi::common.generate_text') }}"></textarea>
            <div style="font-size: 13px;color: #777;"><i class="bi bi-exclamation-circle"></i> 简单模式系统会在商品名称的基础上添加好提示词</div>
            <div style="font-size: 13px;color: #777;"><i class="bi bi-exclamation-circle"></i> 最终提示词：<span class="prompt-line-help"></span></div>
          </div>
          <div class="tab-pane fade" id="tabs-ai-pro">
            <textarea class="form-control mb-1" rows="2" name="prompt_pro" placeholder="{{ __('BkAi::common.generate_text') }}"></textarea>
            <div style="font-size: 13px;color: #777;"><i class="bi bi-exclamation-circle"></i> 专业模式您可以自由发挥，输入您设定的提示词，系统不会添加描述</div>
          </div>
        </div>
        <div class="invalid-feedback">{{ __('common.error_required', ['name' => __('BkAi::common.prompt')]) }}</div>
      </div>

      <div class="col-12">
        <button class="btn btn-outline-primary" type="submit">
          <span class="spinner-border spinner-border-sm ai-design-ing d-none" aria-hidden="true"></span>
          <span role="status" class="ai-design-ing d-none">{{ __('BkAi::common.generate_ing') }}</span>
          <span class="ai-design-text"><i class="bi bi-magic"></i> {{ __('BkAi::common.generate') }}</span>
        </button>
      </div>
    </form>

    <div class="mt-3">
      <label class="form-label">{{ __('BkAi::common.generated_content') }}</label>
      <textarea class="form-control generated-content mb-1" rows="6" readonly placeholder="{{ __('BkAi::common.generated_content') }}"></textarea>
      {{-- <span style="color: #999;">{{ __('BkAi::common.confirm_text') }}</span> --}}
      <div style="color: #444;"><i class="bi bi-exclamation-circle"></i> 注意：结果主要取决于提示词，不同的提示词内容差异可能很大，内容格式也许会有偏差或多余的描述性文字，可以确定后在输入框内自行调整</div>
      <div class="mt-3">
        <button class="btn btn-primary confirm-content" style="min-width: 100px" type="button">{{ __('common.confirm') }}</button>
      </div>
    </div>
  </div>

  <script>
    const locale = @json(request('locale'));
    const type = @json(request('type'));
    const productName = @json(request('name'));
    const languages = @json(languages());
    const locales = @json(locales());
    const langNameString = locales.map((item) => item.name).join(',');
    if (productName) {
      switch (type) {
        case 'product_descriptions':
          $('textarea[name="prompt_line"]').text(`${productName}`)
          $('.prompt-line-help').text(`请根据下面我提供的商品名称生成这个商品对应的详情描述： ${productName}。`)
          $('.title').text('AI 商品描述生成');
          break;
        case 'meta_title':
          $('textarea[name="prompt_line"]').text(`${productName}`)
          $('.prompt-line-help').text(`请根据下面提供的商品名称生成 meta title：${productName}。`)
          $('.title').text('AI Meta Title 生成');
          break;
        case 'meta_keywords':
          $('textarea[name="prompt_line"]').text(`${productName}`)
          $('.prompt-line-help').text(`请根据下面提供的商品名称生成 meta keywords：${productName}。`)
          $('.title').text('AI Meta Keywords 生成');
          break;
        case 'meta_description':
          $('textarea[name="prompt_line"]').text(`${productName}`)
          $('.prompt-line-help').text(`请根据下面提供的商品名称生成 meta description：${productName}。`)
          $('.title').text('AI Meta Description 生成');
          break;
      }
    }

    $('textarea[name="prompt_line"]').on('input', function() {
      var val = $(this).val();
      if (val) {
        switch (type) {
          case 'product_descriptions':
            $('.prompt-line-help').text(`请根据下面我提供的商品名称生成这个商品对应的详情描述： ${val}。`)
            break;
          case 'meta_title':
            $('.prompt-line-help').text(`请根据下面提供的商品名称生成 meta title：${val}。`)
            break;
          case 'meta_keywords':
            $('.prompt-line-help').text(`请根据下面提供的商品名称生成 meta keywords：${val}。`)
            break;
          case 'meta_description':
            $('.prompt-line-help').text(`请根据下面提供的商品名称生成 meta description：${val}。`)
            break;
        }
      } else {
        $('.prompt-line-help').text('')
      }
    });

    $('textarea[name="prompt_line"]').trigger('input');

    $('.magic-ai-form').on('submit', function(e) {
      e.preventDefault();
      var tab = $(this).find('.nav-link.active').data('bs-target');
      var prompt = tab == '#tabs-ai-line' ? $(this).find('.prompt-line-help').text() : $(this).find('textarea[name="prompt_pro"]').val();
      var langVal = $(this).find('select[name="lang"]').val() || 'all';

      if (!prompt) {
        return layer.msg('提示词不能为空', ()=>{});
      }

      if (langVal == 'current') {
        prompt += ` --请以${locale}语言回答`;
      } else {
        prompt += ` --请用${langNameString}语言回答，并使用以下格式分隔:\n${locales.map((item) => `### ${item.code}|${item.name}:\n内容\n----------`).join('\n')}`;
      }

      var generatedContent = $(this).find('.generated-content');
      $('.ai-design-ing').removeClass('d-none').siblings('.ai-design-text').addClass('d-none');
      $('.magic-ai-form').find('button').prop('disabled', true);
      $http.post('{{ admin_route('plugin.bk_ai.generate') }}', { prompt: prompt }, {hload: true}).then((res) => {
        let data = JSON.parse(res);
        console.log(data);
        if (data.data.status == 'fail') {
          layer.msg(data.msg);
          return;
        }

        $('.has-quota').text(data.data.has_quota);
        if (data.data.has_quota < 10) {
          $('.set-quota').removeClass('d-none');
        }
        $('.generated-content').val(data.data.choices[0].message.content);
      }).finally(() => {
        $('.ai-design-ing').addClass('d-none').siblings('.ai-design-text').removeClass('d-none');
        $('.magic-ai-form').find('button').prop('disabled', false);
      });
    });

    $('.confirm-content').on('click', function() {
      var content = $('.generated-content').val();
      if (!content) {
        return layer.msg('没有内容', ()=>{});
      }
      parent.postMessage({ type: type, data: content }, '*');
    });

    $(function () {
      $http.get('{{ admin_route('plugin.bk_ai.get_quota') }}', null, {hload: true}).then((res) => {
        let data = JSON.parse(res);
        $('.has-quota').text(data.data);
        if (data.data < 10) {
          $('.set-quota').removeClass('d-none');
        }
      })
    });
  </script>
  <style>
    .ai-input-tabs .nav-link.active {
      background-color: #fff;
    }
  </style>
</body>
</html>