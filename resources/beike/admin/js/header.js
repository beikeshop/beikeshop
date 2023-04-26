/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-08-16 18:47:18
 * @LastEditTime  2023-04-28 11:18:47
 */

$(function () {
  // 响应式下弹窗菜单交互
  $(document).on("click", ".mobile-open-menu", function () {
    $('.sidebar-box').toggleClass('active');
  });

  // 点击 sidebar-box 内 除 sidebar-info 以外的地方关闭弹窗
  $('.sidebar-box').on("click", function (e) {
    if (!$(e.target).parents(".sidebar-info").length) {
      $(".sidebar-box").removeClass("active");
    }
  });

  $(document).on("focus", ".search-wrap .input-wrap input", function () {
    $(this).parents('.input-wrap').addClass("active");
  });

  $(document).on("focus", ".search-wrap .input-wrap .close-icon", function () {
    $(this).siblings('input').val('');
    $(this).parents('.input-wrap').removeClass("active");
    $('.dropdown-search .common-links').html('');
    $('.dropdown-search').hide().siblings('.dropdown-wrap').show();
  });

  let timer = null;
  let searchLinksLength = 0;

  $('#header-search-input').on("keyup", function (key) {
    const val = $(this).val();

    // 排除方向键
    if (key.keyCode == 38 || key.keyCode == 40 || key.keyCode == 37 || key.keyCode == 39) {
      return;
    }

    // 回车键
    if (key.keyCode == 13) {
      const $activeItem = $('.dropdown-search .common-links .dropdown-item.active');
      if ($activeItem.length) {
        window.location.href = $activeItem.attr('href');
      }
      return;
    }

    $('.dropdown-search').hide().find('.common-links').html('');

    if (val == '') {
      $('.search-ing').hide().siblings('.dropdown-wrap').show();
      return;
    }

    $('.search-ing').show().siblings('.dropdown-wrap').hide();

    clearTimeout(timer);
    timer = setTimeout(() => {
      if (!$('#header-search-input').val()) return;
      searchApi(val)
    }, 300);
  })

  $('#header-search-input').on("keydown", function (key) {
    if (key.keyCode == 38 || key.keyCode == 40) {
      const $dropdownItem = $('.dropdown-search .common-links .dropdown-item');
      const dropdownSearchLinksTop = $('.dropdown-search .common-links').offset().top;
      const dropdownSearchHeight = $('.dropdown-search').height() - 34;
      const dropdownSearchTop = $('.dropdown-search').offset().top + dropdownSearchHeight;
      const index = $dropdownItem.index($('.dropdown-search .common-links .dropdown-item.active'));

      if (key.keyCode == 38) {
        if (index == '-1' || index == 0) {
          $dropdownItem.removeClass('active').eq(searchLinksLength - 1).addClass('active');
          $('.dropdown-search').scrollTop($('.dropdown-search .common-links').height());
        } else {
          $dropdownItem.removeClass('active').eq(index - 1).addClass('active');
          const activeTop = $('.dropdown-search .common-links .dropdown-item.active').offset().top;

          if (activeTop < dropdownSearchTop - dropdownSearchHeight) {
            $('.dropdown-search').scrollTop(activeTop - dropdownSearchLinksTop + 30);
          }
        }
      }

      if (key.keyCode == 40) {
        if (index == '-1' || index == searchLinksLength - 1) {
          $dropdownItem.removeClass('active').eq(0).addClass('active');
          $('.dropdown-search').scrollTop(0);
        } else {
          $dropdownItem.removeClass('active').eq(index + 1).addClass('active');
          const activeTop = $('.dropdown-search .common-links .dropdown-item.active').offset().top;
          if (activeTop > dropdownSearchTop) {
            $('.dropdown-search').scrollTop(activeTop - dropdownSearchLinksTop - dropdownSearchHeight + 40);
          }
        }
      }
    }
  })

  const searchApi = (val) => {
    $http.get(`menus?keyword=${val}`, null, {hload: true}).then((res) => {
      searchLinksLength = res.length;
      $('.dropdown-search').show().siblings('.dropdown-wrap').hide();
      $('.header-search-no-data').hide();
      if (res.length) {
        $('.dropdown-search .common-links').html(res.map((item) => {
          return `<a href="${item.url}" class="dropdown-item"><span><i class="bi bi-link-45deg"></i></span> ${item.title}</a>`
        }).join(''))
      } else {
        $('.header-search-no-data').show();
      }
    }).finally(() => {
      $('.search-ing').hide();
    })
  }

  // 点击 search-wrap 以外的地方关闭搜索框
  $(document).on("click", function (e) {
    if (!$(e.target).parents(".search-wrap").length) {
      $(".search-wrap .input-wrap").removeClass("active");
    }
  });

  let updatePop = null;

  $('.update-btn').click(function() {
    updatePop = layer.open({
      type: 1,
      title: lang.text_hint,
      area: ['400px'],
      content: $('.update-pop'),
    });
  });

  $('.update-pop .btn-outline-secondary').click(function() {
    layer.close(updatePop)
  });
});
