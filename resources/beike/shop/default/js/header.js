/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-08-16 18:47:18
 * @LastEditTime  2022-11-04 16:00:27
 */

$(function () {
  // 搜索弹出层交互
  const myOffcanvas = document.getElementById("offcanvas-search-top");
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

  // 响应式下弹窗菜单交互
  $(document).on("click", ".mobile-open-menu", function () {
    const offcanvasMobileMenu = new bootstrap.Offcanvas('#offcanvas-mobile-menu')
    offcanvasMobileMenu.show()
  });

  // 右侧购物车弹出层内交互
  $(document).on("click", "#offcanvas-right-cart .select-wrap", function () {
    const [unchecked, checked] = ['bi bi-circle', 'bi bi-check-circle-fill'];
    const productListAll = $('#offcanvas-right-cart .product-list').length;

    const isChecked = $(this).children('i').hasClass(unchecked);
    $(this).children('i').prop('class', isChecked ? checked : unchecked);

    const checkedProduct = $('#offcanvas-right-cart .offcanvas-right-products i.bi-check-circle-fill').length;

    if ($(this).hasClass('all-select')) {
      const isAll = $('.all-select i').hasClass(checked);
      $('#offcanvas-right-cart .product-list').find('.select-wrap i').prop('class', isAll ? checked : unchecked)
    } else {
      $('.offcanvas-footer .all-select i').prop('class', productListAll == checkedProduct ? checked : unchecked);
    }

    const checkedIds = $('#offcanvas-right-cart .product-list').map(function() {
      return $(this).find('i.bi-check-circle-fill').data('id');
    }).get();

    if (!checkedIds.length) {
      $('#offcanvas-right-cart .to-checkout').addClass('disabled')
    } else {
      $('#offcanvas-right-cart .to-checkout').removeClass('disabled')
    }

    $http.post(`/carts/select`, {cart_ids: checkedIds}, {hload: true}).then((res) => {
      updateMiniCartData(res);
    })
  });

  // 右侧购物车弹出层内交互
  $(document).on("change", "#offcanvas-right-cart .price input", function () {
    const [id, sku_id, quantity] = [$(this).data('id'), $(this).data('sku'), $(this).val() * 1];
    if ($(this).val() === '') $(this).val(1);

    $http.put(`/carts/${id}`, {quantity: quantity, sku_id}, {hload: true}).then((res) => {
      updateMiniCartData(res);
    })
  })

  function updateMiniCartData(res) {
    $('.offcanvas-right-cart-count').text(res.data.quantity);
    $('.offcanvas-right-cart-amount').text(res.data.amount_format);
  }

  // 导航菜单防止小屏幕下(非手机端)，配置列数过多 显示错误
  $('.menu-wrap > ul > li').each(function(index, el) {
    if ($(el).children('.dropdown-menu').length) {
      const offsetLeft = $(el).children('.dropdown-menu').offset().left;
      const width = $(el).children('.dropdown-menu').width();
      const windowWidth = $(window).width();

      if (offsetLeft < 0) {
        $(el).addClass('position-static')
        .children('.dropdown-menu')
        .css({'left': (windowWidth - width) / 2, 'transform': 'translate(0, 0.5rem)'});
      }
    }
  });
});
