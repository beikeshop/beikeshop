@push('footer')
  <script>
    var locales = @json(locales());

    $(function() {
      $('.description-wrap').find('ul.nav.nav-tabs').append('<button type="button" class="btn btn-outline-primary btn-sm ms-4 mb-1 generate-descriptions" data-type="product_descriptions"><i class="bi bi-magic"></i> {{ __('BkAi::common.magic_ai') }}</button>');
      $('.seo-wrap').find('input[name="descriptions[{{ locale() }}][meta_title]"]').parents('.col-auto').siblings('.col-form-label').after('<button type="button" class="btn btn-outline-primary btn-sm ms-4 mb-1 generate-seo" data-type="meta_title"><i class="bi bi-magic"></i> {{ __('BkAi::common.magic_ai') }}</button>');
      $('.seo-wrap').find('textarea[name="descriptions[{{ locale() }}][meta_keywords]"]').parents('.col-auto').siblings('.col-form-label').after('<button type="button" class="btn btn-outline-primary btn-sm ms-4 mb-1 generate-seo" data-type="meta_keywords"><i class="bi bi-magic"></i> {{ __('BkAi::common.magic_ai') }}</button>');
      $('.seo-wrap').find('textarea[name="descriptions[{{ locale() }}][meta_description]"]').parents('.col-auto').siblings('.col-form-label').after('<button type="button" class="btn btn-outline-primary btn-sm ms-4 mb-1 generate-seo" data-type="meta_description"><i class="bi bi-magic"></i> {{ __('BkAi::common.magic_ai') }}</button>');

      $(document).on('click', '.generate-descriptions', function() {
        const type = $(this).data('type');
        const locale = $('.description-wrap .nav-link.active').data('bs-target').replace('#tab-descriptions-', '');
        openBkAi(type, locale);
      });

      $(document).on('click', '.generate-seo', function() {
        const type = $(this).data('type');
        openBkAi(type);
      });
    });

    // 接收 postMessage 信息
    function extractContent(section) {
      const matches = section.trim().match(/###\s*(\w+)\|.*:\s*(.*)/s);
      if (matches) {
        const language = matches[1];
        let content = matches[2].trim();
        content = content.replace(/\n/g, '<br>');
        return { language, content };
      }
      return null;
    }

    // 处理 product_descriptions 类型的数据
    function handleProductDescriptions(sections, data) {
      if (sections.length > 1) {
        sections.forEach(section => {
          const result = extractContent(section);
          if (result) {
            const { language, content } = result;
            const id = $('#tab-descriptions-' + language).find('textarea[name="descriptions[' + language + '][content]"]').attr('id');
            tinymce.get(id).setContent(content);
          }
        });
      } else {
        var target = $('#tab-descriptions .nav-link.active').data('bs-target');
        var locale = target.replace('#tab-descriptions-', '');
        var id = $('#tab-descriptions-' + locale).find('textarea[name="descriptions[' + locale + '][content]"]').attr('id');
        var content = data;
        content = content.replace(/\n/g, '<br>');
        tinymce.get(id).setContent(content);
      }
      layer.closeAll();
    }

    // 处理 meta 信息的类型数据（meta_title, meta_keywords, meta_description）
    function handleMetaInfo(sections, data, type) {
      sections.forEach(section => {
        const result = extractContent(section);
        if (result) {
          const { language, content } = result;
          console.log(content);
          const label = type === 'meta_title' ? 'input' : 'textarea';
          $('.seo-wrap').find(label + '[name="descriptions[' + language + '][' + type + ']"]').val(content);
        }
      });
      layer.closeAll();
    }

    // 监听 message 事件
    window.addEventListener('message', function(event) {
      if (event.origin !== window.location.origin) {
        return;
      }

      if (event.data.data) {
        var sections = event.data.data.split('----------');
      }

      // 处理 product_descriptions 类型的数据
      if (event.data.type === 'product_descriptions') {
        handleProductDescriptions(sections, event.data.data);
      }

      // 处理 meta 信息的数据
      if (['meta_title', 'meta_keywords', 'meta_description'].includes(event.data.type)) {
        handleMetaInfo(sections, event.data.data, event.data.type);
      }
    });

    function openBkAi(type, locale = '') {
      const lang = $('html').attr('lang');
      const base = document.querySelector('base').href;
      const name = $(`input[name="descriptions[${lang}][name]"]`).val();
      if (!name) {
        layer.msg('请先输入商品名称', () => {});
        $('button[data-bs-target="#tab-basic"]').click();
        return;
      }

      layer.open({
        type: 2,
        title: '{{ __('BkAi::common.magic_ai') }}',
        shadeClose: false,
        skin: 'bk-ai-box',
        scrollbar: false,
        maxmin: true,
        shade: 0.4,
        resize: false,
        area: ['530px', '710px'],
        content: `${base}/bk_ai/index?type=${type}&locale=${locale}&name=${name}`,
      });
    }
  </script>
@endpush