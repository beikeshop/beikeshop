@extends('admin::layouts.master')

@section('title', $name)

@push('header')
  <script src="{{ asset('vendor/marked/marked.min.js') }}"></script>
  <script src="{{ asset('vendor/highlight/highlight.min.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('vendor/highlight/atom-one-dark.min.css') }}">
@endpush

@section('content')
<div class="row">
  <div class="col-md-7 col-12 answer-wrap">
    <div class="border p-3 bg-white" id="answer">
      <div class="not-answer"><i class="bi bi-activity"></i> {{ __('Openai::common.no_question') }}</div>
    </div>
      <div class="input-group mb-3 mt-4">
        <input type="text" id="ai-input" class="form-control rounded-0 form-control-lg"
          placeholder="{{ __('Openai::common.enter_question') }}" {{ $error ? 'disabled' : '' }} aria-label="{{ __('Openai::common.enter_question') }}"
          aria-describedby="button-addon2">
        <button class="btn btn-primary px-4 rounded-0" {{ $error ? 'disabled' : '' }} type="button" id="ai-submit"><i class="bi bi-send-fill"></i>
          {{ __('common.confirm') }}</button>
      </div>
    </div>
    <div class="col-md-5 col-12">
      <div class="mb-2"><i class="bi bi-megaphone text-secondary fs-3"></i> </div>
        @if ($type != 'own')
        <div class="number-free mb-3 fs-5">{{ __('Openai::common.number_free') }}:
          <span>{{ __('Openai::common.loading') }}</span>
        </div>
        @endif
        @if ($error)
        <div class="alert alert-danger alert-dismissible">
          <i class="bi bi-exclamation-triangle-fill"></i>
          {{ $error }}
        </div>
        @endif
      <div class="text-secondary">{{ $description }}</div>
    </div>
  </div>
  <script>
    let last_page = 0;
    let current_page = 1;

    marked.setOptions({
      highlight: function(code, lang) {
        if (lang && hljs.getLanguage(lang)) {
          return hljs.highlight(lang, code, true).value;
        } else {
          return hljs.highlightAuto(code).value;
        }
      }
    });

    $('#answer').scroll(function() {
      if ($(this).scrollTop() == 0) {
        if (current_page < last_page) {
          if (!$('.text-loading').length) {
            $('#answer').prepend(
              '<div class="text-center py-3 text-secondary text-loading"><i class="bi bi-activity"></i> {{ __('Openai::common.loading') }}</div>'
              );
          }

          clearTimeout(timer);
          var timer = setTimeout(function() {
            loadHistories(current_page + 1);
          }, 300);
        }

        if (current_page == last_page) {
          if (!$('.text-loading').length) {
            $('#answer').prepend(
              '<div class="text-center py-3 text-secondary text-loading"><i class="bi bi-activity"></i> {{ __('Openai::common.no_more') }}</div>'
              );
          }
        }
      }
    });

    $('#answer').height($(window).height() - 260);
    $(document).ready(function() {
      @if ($type != 'own')
        loadQuantities();
      @endif
      loadHistories(1 , function() {
        // 获取 answer .answer-list 内容高度
        let height = 0;
        $('.answer-list').each(function() {
          height += $(this).height();
        })

        let answerHeight = $('#answer').height();

        if (height < answerHeight) {
          loadHistories(current_page + 1);
        }
      });

      $('#ai-input').keydown(function(e) {
        if (e.keyCode == 13) {
          $('#ai-submit').click();
        }
      })

      $('#ai-submit').click(function() {
        var question = $('#ai-input').val();
        if (!question) {
          return;
        }

        const $btn = $(this);
        const btnHtml = $(this).html();
        const loadHtml = '<span class="spinner-border spinner-border-sm"></span>';

        let html = '';

        $.ajax({
          url: `{{ $base }}/completions`,
          type: 'POST',
          headers: {
            'token': '{{ system_setting('base.developer_token') ?? '' }}'
          },
          data: {
            prompt: question,
            domain: config.app_url,
          },
          beforeSend: function() {
            $btn.html(loadHtml).prop('disabled', true)
          },
          complete: function() {
            $btn.html(btnHtml).prop('disabled', false)
          },
          success: function(data) {
            if ($('.not-answer').length) {
              $('.not-answer').remove();
            }

            if (data.error) {
              layer.msg(data.error);
              return;
            }

            $('.number-free span').text(data.available)

            let answer = marked.parse(data.response.choices[0].text);
            html += '<div class="answer-list">',
              html += '<div class="created-at"><span>' + data.created_format + '</span></div>',
              html += '<div class="d-flex mb-2"><div class="text-secondary">{{ __('Openai::common.qa_q') }}：</div><div class="w-100">' + question + '</div></div>',
              html += '<div class="d-flex"><div class="text-secondary">{{ __('Openai::common.qa_a') }}：</div><div class="w-100">' + answer + '</div></div>'
            html += '</div>'

            $('#ai-input').val('');
            $('#answer').append(html);
            $('#answer').scrollTop($('#answer')[0].scrollHeight);
          }
        })
      })
    })

    function loadQuantities() {
      $.ajax({
        url: `{{ $base }}/quantities?domain=${config.app_url}`,
        headers: {
          'token': '{{ system_setting('base.developer_token') ?? '' }}'
        },
        success: function(data) {
          $('.number-free span').text(data.available)
        }
      })
    }

    function loadHistories(page = 1, callback = null) {
      $.ajax({
        url: `{{ $base }}/histories?domain=${config.app_url}&page=${page}`,
        headers: {
          'token': '{{ system_setting('base.developer_token') ?? '' }}'
        },
        success: function(data) {
          $('.text-loading').remove();

          last_page = data.last_page;
          current_page = data.current_page;

          if (data.data && data.data.length) {
            $('.not-answer').remove();

            // data.data 倒叙
            data.data.reverse();

            let html = '';
            data.data.forEach(function(item, index) {
              html += '<div class="answer-list ' + (!index ? 'first' : '') + '">',
                html += '<div class="created-at"><span>' + item.created_format + '</span></div>',
                html += '<div class="d-flex mb-2"><div class="text-secondary">{{ __('Openai::common.qa_q') }}：</div><div class="w-100">' + item.question + '</div></div>',
                html += '<div class="d-flex"><div class="text-secondary">{{ __('Openai::common.qa_a') }}：</div><div class="w-100">' + marked.parse(item.answer) + '</div></div>'
              html += '</div>'
            })

            $('#answer').prepend(html);

            if (page == 1) {
              $('#answer').scrollTop($('#answer')[0].scrollHeight);
            } else {
              $('#answer').scrollTop($('#answer .answer-list.first:eq(1)').offset().top - 100 - $('#answer')
              .offset().top);
            }

            if (callback) {
              callback();
            }
          }
        }
      })
    }
  </script>

  <style>
    body {
      background-color: #f4f4f4;
    }

    #answer {
      overflow-y: auto;
      border-radius: 0.25rem;
    }

    .not-answer {
      text-align: center;
      padding: 100px 0;
      font-size: 20px;
      color: #999;
    }

    .answer-wrap {
      max-width: 740px;
    }

    .answer-list {
      /* padding-bottom: 20px; */
      /* margin-bottom: 20px; */
      white-space: pre-wrap;
      /* border-bottom: 1px solid #eee; */
    }

    .answer-list p {
      margin-bottom: 0;
    }

    .answer-list:last-child {
      /* border-bottom: none; */
      /* margin-bottom: 0; */
      /* padding-bottom: 0; */
    }

    .created-at {
      text-align: center;
      color: #999;
      font-size: 12px;
      margin: 30px 0;
      position: relative;
    }

    .created-at span {
      background-color: #fff;
      padding: 0 10px;
      position: relative;
    }

    .created-at:before {
      content: '';
      display: inline-block;
      width: 100%;
      height: 1px;
      background-color: #eee;
      position: absolute;
      top: 50%;
      left: 0;
    }

    .answer-wrap pre {
      display: block;
      background-color: #f3f3f3;
      padding: .5rem !important;
      overflow-y: auto;
      font-weight: 300;
      font-family: Menlo, monospace;
      border-radius: .3rem;
      margin-bottom: 0;
    }

    .answer-wrap pre {
      background-color: #283646 !important;
    }

    .answer-wrap pre>code {
      border: 0px !important;
      background-color: #283646 !important;
      color: #FFF;
    }

    .answer-wrap ol, .answer-wrap ul, .answer-wrap dl {
      margin-bottom: 0;
      padding-left: 14px;
      line-height: 1;
    }
  </style>
@endsection
