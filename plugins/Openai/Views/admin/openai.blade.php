@extends('admin::layouts.master')

@section('title', $name)

@section('content')
  <div class="row">
    <div class="col-md-7 col-12 answer-wrap">
      <div class="border p-3 bg-white" id="answer"><div class="not-answer"><i class="bi bi-activity"></i> {{ __('Openai::common.no_question') }}</div></div>

      <div class="input-group mb-3 mt-4">
        <input type="text" id="ai-input" class="form-control rounded-0 form-control-lg" placeholder="{{ __('Openai::common.enter_question') }}" aria-label="{{ __('Openai::common.enter_question') }}" aria-describedby="button-addon2">
        <button class="btn btn-primary px-4 rounded-0" type="button" id="ai-submit"><i class="bi bi-send-fill"></i> {{ __('common.confirm') }}</button>
      </div>
    </div>
    <div class="col-md-5 col-12">
      <div class="mb-2"><i class="bi bi-megaphone text-secondary fs-3"></i> </div>
      <div class="number-free mb-3 fs-5">{{ __('Openai::common.number_free') }}: <span>{{ __('Openai::common.loading') }}</span></div>
      <div class="text-secondary">{{ $description }}</div>
    </div>
  </div>
  <script>
    let last_page = 0;
    let current_page = 1;

    $('#answer').scroll(function() {
      if ($(this).scrollTop() == 0) {
        if (current_page < last_page) {
          if (!$('.text-loading').length) {
            $('#answer').prepend('<div class="text-center py-3 text-secondary text-loading"><i class="bi bi-activity"></i> {{ __('Openai::common.loading') }}</div>');
          }

          clearTimeout(timer);
          var timer = setTimeout(function() {
            loadHistories(current_page + 1);
          }, 300);
        }

        if (current_page == last_page) {
          if (!$('.text-loading').length) {
            $('#answer').prepend('<div class="text-center py-3 text-secondary text-loading"><i class="bi bi-activity"></i> {{ __('Openai::common.no_more') }}</div>');
          }
        }
      }
    });

    $('#answer').height($(window).height() - 260);
    $(document).ready(function() {
      loadQuantities();
      loadHistories();

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
          url: `${config.api_url}/api/openai/completions`,
          type: 'POST',
          headers: {
            'token': '{{ system_setting('base.developer_token') ?? '' }}'
          },
          data: {
            prompt: question,
            domain: config.app_url,
          },
          beforeSend: function() { $btn.html(loadHtml).prop('disabled', true) },
          complete: function() { $btn.html(btnHtml).prop('disabled', false) },
          success: function(data) {
            if ($('.not-answer').length) {
              $('.not-answer').remove();
            }

            if (data.error) {
              layer.msg(data.error);
              return;
            }

            $('.number-free span').text(data.available)

            let answer = data.response.choices[0].text.trim();
            html += '<div class="answer-list">',
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
        url: `${config.api_url}/api/openai/quantities?domain=${config.app_url}`,
        headers: {
          'token': '{{ system_setting('base.developer_token') ?? '' }}'
        },
        success: function(data) {
          $('.number-free span').text(data.available)
        }
      })
    }

    function loadHistories(page = 1) {
      $.ajax({
        url: `${config.api_url}/api/openai/histories?domain=${config.app_url}&page=${page}`,
        headers: {
          'token': '{{ system_setting('base.developer_token') ?? '' }}'
        },
        success: function(data) {
          $('.text-loading').remove();
          last_page = data.last_page;
          current_page = data.current_page;

          if (data.data.length) {
            $('.not-answer').remove();

            let html = '';
            data.data.forEach(function(item, index) {
              html += '<div class="answer-list '+ (!index ? 'first' : '') +'">',
              html += '<div class="d-flex mb-2"><div class="text-secondary">{{ __('Openai::common.qa_q') }}：</div><div class="w-100">' + item.question + '</div></div>',
              html += '<div class="d-flex"><div class="text-secondary">{{ __('Openai::common.qa_a') }}：</div><div class="w-100">' + item.answer + '</div></div>'
              html += '</div>'
            })

            $('#answer').prepend(html);
            if (page == 1) {
              $('#answer').scrollTop($('#answer')[0].scrollHeight);
            } else {
              $('#answer').scrollTop($('#answer .answer-list.first:eq(1)').offset().top - 100 - $('#answer').offset().top);
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
      white-space: pre-wrap;
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
      padding-bottom: 20px;
      margin-bottom: 20px;
      border-bottom: 1px solid #eee;
    }

    .answer-list:last-child {
      border-bottom: none;
    }
  </style>
@endsection
