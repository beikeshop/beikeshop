/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-08-16 18:47:18
 * @LastEditTime  2022-11-04 16:00:27
 */

$(function () {
  // 响应式下弹窗菜单交互
  $(document).on("click", ".mobile-open-menu", function () {
    const offcanvasMobileMenu = new bootstrap.Offcanvas('#offcanvas-mobile-menu')
    offcanvasMobileMenu.show()
  });
});
