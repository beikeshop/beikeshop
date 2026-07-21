/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @Date          2022-08-26 18:18:22
 * @LastEditTime  2024-12-19 22:42:24
 */

import http from "../../../js/http";
window.$http = http;
import common from "./common";
window.bk = common;
import "./autocomplete";
import "./header";
import "./admin-dashboard";
import "./bootstrap-validation";

const base = document.querySelector('base').href;
const asset = document.querySelector('meta[name="asset"]').content;
const editor_language = document.querySelector('meta[name="editor_language"]')?.content || 'zh_cn';
const loginUrl = new URL('login', base).toString();
window.__bkAdminAuthRedirecting = false;

const handleAuthExpired = (response) => {
  const status = Number(response?.status);
  if (status !== 401 && status !== 419) {
    return false;
  }

  if (window.__bkAdminAuthRedirecting) {
    return true;
  }

  window.__bkAdminAuthRedirecting = true;
  const redirect = response?.data?.redirect || response?.responseJSON?.redirect || loginUrl;
  window.location.href = redirect;

  return true;
}
window.bkHandleAuthExpired = handleAuthExpired;

const registerAuthExpiredInterceptor = (client) => {
  if (!client?.interceptors) return;

  client.interceptors.response.use(
    (response) => response,
    (error) => {
      handleAuthExpired(error?.response);

      return Promise.reject(error);
    }
  );
};

registerAuthExpiredInterceptor(window.axios);
registerAuthExpiredInterceptor($http?.axiosApi);

$(document).on('click', '.open-file-manager', function(event) {
  bk.fileManagerIframe(images => {
    if (!$(this).find('img').length) {
      $(this).append('<img src="' + images[0].url + '" class="img-fluid">');
      $(this).find('.img-components-remove').removeClass('d-none');
      $(this).find('i.icon-plus').remove();
    } else {
      $(this).find('img').prop('src', images[0].url);
    }
    $(this).next('input').val(images[0].path)
    $(this).next('input')[0].dispatchEvent(new Event('input'));
  });
});

$(document).on('click', '.row-link', function(e) {
  const url = $(this).data('to-url');
  const target = $(this).data('target');
  if (url) {
    if (target === '_blank') {
      window.open(url);
    } else {
      window.location.href = url;
    }
  }
});

if (typeof Vue != 'undefined') {
  Vue.prototype.thumbnail = function thumbnail(image) {
    if (!image) {
      return config.placeholder;
    }

    if (image.indexOf('.mp4') !== -1) {
      return asset + 'image/video-icon.png';
    }

    // 判断 image 是否以 http 开头
    if (image.indexOf('http') === 0) {
      return image;
    }

    return asset + image;
  };

  Vue.prototype.stringLengthInte = function stringLengthInte(text, length) {
    return bk.stringLengthInte(text, length)
  };
}

$(function () {
  bk.versionUpdateTips();

  $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    // beforeSend: function() { layer.load(2, {shade: [0.3,'#fff'] }); },
    // complete: function() { layer.closeAll('loading'); },
    error: function(xhr, ajaxOptions, thrownError) {
      if (handleAuthExpired(xhr)) {
        return;
      }

      if (xhr.responseJSON?.message) {
        layer.msg(xhr.responseJSON.message,() => {})
      }
    },
  });

  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

  autoActiveTab()
  tinymceInit()
  inputLocaleTranslate()
  checkRemoveCopyRight()
  pageBottomBtns()
});

const inputLocaleTranslate = () => {
  $('.translate-btn').click(function () {
    var from = $(this).siblings('.from-locale-code').val();
    var to = $(this).siblings('.to-locale-code').val();
    let $parents = $(this).parents('.input-locale-wrap').length ? $(this).parents('.input-locale-wrap') : $(this).parents('.col-auto');
    var text = $parents.find('.input-' + from).val();
    if (!text) {
      return layer.msg(lang.translate_form, () => {});
    }

    // 发请求之前删除所有错样式
    $http.post('translation', {from, to, text}).then((res) => {
      $('.translation-error-text').remove()

      res.data.forEach((e) => {
        $parents.find('.input-' + e.locale).removeClass('translation-error');

        if (e.error) {
          $parents.find('.input-' + e.locale).addClass('translation-error');
          $parents.find('.input-' + e.locale).parents('.input-for-group').after('<div class="invalid-feedback translation-error-text mb-1 d-block" style="margin-left: 86px">' + e.error + '</div>');
        } else {
          $parents.find('.input-' + e.locale).val(e.result);
        }
      });
    })
  });
}

const tinymceInit = () => {
  if (typeof tinymce == 'undefined') {
    return;
  }

  const dataBsTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';

  tinymce.init({
    selector: '.tinymce',
    language: editor_language,
    branding: false,
    height: 500,
    skin: dataBsTheme == 'dark' ? 'oxide-dark' : 'oxide',
    content_css: dataBsTheme == 'dark' ? 'dark' : 'default',
    convert_urls: false,
    inline: false,
    relative_urls: false,
    valid_children: '+body[style]', // 编辑模式下允许写 <style></style>
    plugins: "link lists fullscreen table hr wordcount image imagetools code",
    menubar: "",
    toolbar: "undo redo | toolbarImageButton | lineheight | bold italic underline strikethrough | forecolor backcolor | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | formatpainter removeformat | charmap emoticons | preview | template link anchor table toolbarImageUrlButton | fullscreen code",
    // contextmenu: "link image imagetools table",
    toolbar_items_size: 'small',
    image_caption: true,
    imagetools_toolbar: '',
    toolbar_mode: 'wrap',
    font_formats:
      "微软雅黑='Microsoft YaHei';黑体=黑体;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Georgia=georgia,palatino;Helvetica=helvetica;Times New Roman=times new roman,times;Verdana=verdana,geneva",
    fontsize_formats: "10px 12px 14px 18px 24px 36px 48px 56px 72px 96px",
    lineheight_formats: "1 1.1 1.2 1.3 1.4 1.5 1.7 2.4 3 4",
    setup: function(ed) {
      ed.ui.registry.addButton('toolbarImageButton',{
        icon: 'image',
        onAction:function() {
          bk.fileManagerIframe(images => {
            if (images.length) {
              images.forEach(e => {
                if (e.mime == 'video/mp4') {
                  ed.insertContent(`<video src='${e.path}' controls loop muted class="img-fluid" />`);
                } else {
                  // 创建 Image 对象获取真实尺寸
                  const img = new Image();
                  img.onload = function() {
                    const width = img.naturalWidth;
                    const height = img.naturalHeight;

                    // 插入到 TinyMCE
                    ed.insertContent(`<img src="${e.path}" class="img-fluid" data-mce-src="${e.origin_url}" width="${width}" height="${height}" />`);
                  };
                  img.src = e.path;
                }
              });
            }
          })
        }
      });

      // 粘贴的图片上传
      ed.on('paste', function (e) {
        const doc = new DOMParser().parseFromString(e.clipboardData.getData('text/html'), 'text/html');
        const urls = Array.from(doc.querySelectorAll('img')).map(img => img.src);

        if (urls.length) {
          srcUploadImage(ed, doc, urls);
        }

        if (e.clipboardData && e.clipboardData.items) {
          var items = e.clipboardData.items;
          for (var i = 0; i < items.length; i++) {
            if (items[i].type.indexOf('image') !== -1) {
              // 获取图像文件
              var file = items[i].getAsFile();
              // 创建 FormData 对象
              var formData = new FormData();
              formData.append('file', file);
              formData.append('type', 'image');

              uploadImage(ed, formData);

              e.preventDefault();
            }
          }
        }
      });
    }
  });
}

window.tinymceInit = tinymceInit;

const srcUploadImage = (ed, doc, urls) => {
  setTimeout(() => {
    var currentContent = ed.getContent();

    $http.post(`catch_images`, {source: urls, type: 'image'}).then((res) => {
      // 这个接口会返回 一组图片地址，每一个组的结构是 一个 value 和 一个 origin，根据 origin 对比原来的 src，如果相同则替换, 最终 ed.setContent
      const imageMapping = res.data.reduce((acc, imgData) => {
        acc[imgData.origin] = imgData.url; // 将原地址与新地址映射
        return acc;
      }, {});

      // 使用 DOMParser 解析当前内容并更新图片
      const parser = new DOMParser();
      const parsedDoc = parser.parseFromString(currentContent, 'text/html');

      // 替换原有图片 src
      parsedDoc.querySelectorAll('img').forEach((img) => {
        const originalSrc = img.src; // 获取原始 src
        if (imageMapping[originalSrc]) {
          img.src = imageMapping[originalSrc]; // 替换为新地址
        }

        // 移除 crossorigin 属性
        if (img.hasAttribute('crossorigin')) {
          img.removeAttribute('crossorigin');
        }

        // 移除  data-mce-src 属性
        if (img.hasAttribute('data-mce-src')) {
          img.removeAttribute('data-mce-src');
        }
      });

      // 设置更新后的内容回编辑器
      ed.setContent(parsedDoc.body.innerHTML, { format: 'html' });
      ed.selection.select(ed.getBody(), true); // 保持光标在末尾
    })
  }, 100);
}

const uploadImage = (ed, formData, type, fileName) => {
  $http.post('files', formData).then(res => {
    if (type == 'file') {
      ed.insertContent(`<a href='${res.data.url}' target='_blank'>${fileName}</a>`);
      return;
    }

    ed.insertContent(`<img src='${res.data.url}' class="img-fluid" />`);
  })
}

const autoActiveTab = () => {
  const tab = bk.getQueryString('tab');

  if (tab) {
    if ($(`a[href="#${tab}"]`).length) {
      $(`a[href="#${tab}"]`)[0].click()
      return;
    }

    if ($(`button[data-bs-target="#${tab}"]`).length) {
      $(`button[data-bs-target="#${tab}"]`)[0].click()
    }
  }
}

const pageBottomBtns = () => {
  if ($('.page-bottom-btns').length && $('.page-bottom-btns').html().trim()) {
    const contentInfoTop = $('.content-info').offset().top + $('.content-info').height();

    $('#content').css({'padding-bottom': '6rem'})
    $('.page-bottom-btns').css({'left': $('#content').offset().left})
    $('.page-bottom-btns').fadeIn(150)
  }
}

// 检查是否非法移除版权
const checkRemoveCopyRight = () => {
  let isRemove = false;

  // 被注释或删除
  if (!$('#copyright-text').length) {
    isRemove = true;
  }

  // 被隐藏
  if ($('#copyright-text').css('display') === 'none') {
    isRemove = true;
  }

  // 被去除版权中 BeikeShop 文字（仅在未购买License时检查）
  if (!config.has_license && $('#copyright-text').text().indexOf('BeikeShop') === -1) {
    isRemove = true;
  }

  if (!config.has_license && isRemove) {
    $('.warning-copyright').removeClass('d-none')
    if (!$('.warning-copyright').length) {
      $('.header-content .header-right .navbar-right').prepend('<div class="alert alert-warning mb-0 warning-copyright"><i class="bi bi-exclamation-triangle-fill"></i> 请保留网站底部版权，或前往 <a href="https://beikeshop.com/vip/subscription?type=tab-license" target="_blank">购买授权</a></div>')
    }
  }
}
