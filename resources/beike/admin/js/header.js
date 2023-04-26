/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-08-16 18:47:18
 * @LastEditTime  2023-04-26 19:45:54
 */

$(function () {
  // 响应式下弹窗菜单交互
  $(document).on("click", ".mobile-open-menu", function () {
    const offcanvasMobileMenu = new bootstrap.Offcanvas('#offcanvas-mobile-menu')
    offcanvasMobileMenu.show()
  });

  $(document).on("focus", ".search-wrap .input-wrap input", function () {
    $(this).parents('.input-wrap').addClass("active");
  });

  $(document).on("focus", ".search-wrap .input-wrap .close-icon", function () {
    $(this).parents('.input-wrap').removeClass("active");
    $(this).siblings('input').val('');
  });

  // 点击 search-wrap 以外的地方关闭搜索框
  $(document).on("click", function (e) {
    if (!$(e.target).parents(".search-wrap").length) {
      $(".search-wrap .input-wrap").removeClass("active");
    }
  });
});
