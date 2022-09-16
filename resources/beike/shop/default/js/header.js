/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-08-16 18:47:18
 * @LastEditTime  2022-09-16 20:56:58
 */

// offcanvas-search-top
$(function () {
  var myOffcanvas = document.getElementById("offcanvas-search-top");
  if (myOffcanvas) {
    myOffcanvas.addEventListener("shown.bs.offcanvas", function () {
      $("#offcanvas-search-top input").focus();
      $("#offcanvas-search-top input").keydown(function (e) {
        if (e.keyCode == 13) {
          if ($(this).val() != "") {
            location.href = "products/search?keyword=" + $(this).val();
          }
        }
      });
    });
  }

  $(document).on("click", ".mobile-open-menu", function () {
    const offcanvasMobileMenu = new bootstrap.Offcanvas('#offcanvas-mobile-menu')
    offcanvasMobileMenu.show()
  });
});
