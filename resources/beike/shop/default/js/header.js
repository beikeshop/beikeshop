/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-08-16 18:47:18
 * @LastEditTime  2023-12-23 12:52:09
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

  // 购物车侧边栏弹出
  $(document).on("click", ".btn-right-cart", function () {
    if (!config.isLogin && !config.guestCheckout) {
      bk.openLogin()
      return;
    }

    const currentUrl = window.location.pathname;
    if (currentUrl == '/checkout' || currentUrl == '/carts') {
      location.href = '/carts';
      return;
    }

    const offcanvasRightCart = new bootstrap.Offcanvas('#offcanvas-right-cart')
    offcanvasRightCart.show()
  });

  // 侧边栏购物车删除商品
  $(document).on('click', '.offcanvas-products-delete', function () {
    const id = $(this).data('id');

    $http.delete(`carts/${id}`).then((res) => {
      $(this).parents('.product-list').remove();
      if (!res.data.quantity) {
        $('.cart-badge-quantity').hide();
        $('.empty-cart').removeClass('d-none');
      } else {
        $('.cart-badge-quantity').show().html(res.data.quantity > 99 ? '99+' : res.data.quantity);
      }

      $('.offcanvas-right-cart-count').text(res.data.quantity);
      $('.offcanvas-right-cart-amount').text(res.data.amount_format);
    })
  })

  // 响应式下弹窗菜单交互
  $(document).on("click", ".mobile-open-menu", function () {
    const offcanvasMobileMenu = new bootstrap.Offcanvas('#offcanvas-mobile-menu')
    offcanvasMobileMenu.show()
  });

  // 右侧购物车弹出层内交互
  $(document).on("click", "#offcanvas-right-cart .product-list .select-wrap", function () {
    const [unchecked, checked] = ['bi bi-circle', 'bi bi-check-circle-fill'];
    const productListAll = $('#offcanvas-right-cart .product-list').length;
    const cartId = $(this).find('i.bi').data('id');

    const isChecked = $(this).children('i').hasClass(unchecked);
    $(this).children('i').prop('class', isChecked ? checked : unchecked);

    const checkedProduct = $('#offcanvas-right-cart .offcanvas-right-products i.bi-check-circle-fill').length;

    $('.offcanvas-footer .all-select i').prop('class', productListAll == checkedProduct ? checked : unchecked);

    const checkedIds = $('#offcanvas-right-cart .product-list').map(function () {
      return $(this).find('i.bi-check-circle-fill').data('id');
    }).get();

    if (!checkedIds.length) {
      $('#offcanvas-right-cart .to-checkout').addClass('disabled')
    } else {
      $('#offcanvas-right-cart .to-checkout').removeClass('disabled')
    }

    $http.post(`/carts/${isChecked ? 'select' : 'unselect'}`, {
      cart_ids: [cartId]
    }, {
      hload: true
    }).then((res) => {
      updateMiniCartData(res);
    })
  });

  $(document).on("click", "#offcanvas-right-cart .all-select", function () {
    const [unchecked, checked] = ['bi bi-circle', 'bi bi-check-circle-fill'];

    const checkedIds = $('#offcanvas-right-cart .product-list').map(function () {
      return $(this).find('i.bi').data('id');
    }).get();

    const isChecked = $(this).children('i').hasClass(unchecked);
    $(this).children('i').prop('class', isChecked ? checked : unchecked);

    $('#offcanvas-right-cart .product-list').find('.select-wrap i').prop('class', isChecked ? checked : unchecked);

    if (!isChecked) {
      $('#offcanvas-right-cart .to-checkout').addClass('disabled')
    } else {
      $('#offcanvas-right-cart .to-checkout').removeClass('disabled')
    }

    $http.post(`/carts/${isChecked ? 'select' : 'unselect'}`, {
      cart_ids: checkedIds
    }, {
      hload: true
    }).then((res) => {
      updateMiniCartData(res);
    })
  })

  // 右侧购物车弹出层内交互
  $(document).on("change", "#offcanvas-right-cart .price input", function () {
    const [id, sku_id, quantity] = [$(this).data('id'), $(this).data('sku'), $(this).val() * 1];
    if ($(this).val() === '') $(this).val(1);
    let that = this;
    $http.put(`/carts/${id}`, {
      quantity: quantity,
      sku_id
    }, {
      hload: true
    }).then((res) => {
       if(res.status != 'success'){
        layer.msg(res.message)
        $(that).val(res.data.quantity);
      }else {
        updateMiniCartData(res);
      }
    })
  })

  function updateMiniCartData(res) {
    $('.offcanvas-right-cart-count').text(res.data.quantity);
    $('.offcanvas-right-cart-amount').text(res.data.amount_format);
  }

  // 导航菜单防止小屏幕下(非手机端)，配置列数过多 显示错误
  $('.menu-wrap > ul > li').each(function (index, el) {
    if ($(el).children('.dropdown-menu').length) {
      const offsetLeft = $(el).children('.dropdown-menu').offset().left;
      const width = $(el).children('.dropdown-menu').width();
      const offsetRight = offsetLeft + width;
      const windowWidth = $(window).width();

      if (offsetLeft < 0 || offsetRight > windowWidth) {
        $(el).addClass('position-static')
          .children('.dropdown-menu')
          .css({
            'left': (windowWidth - width) / 2,
            'transform': 'translate(0, 0.5rem)'
          });
      }
    }
  });

  // 手机端个人中心弹出菜单
  (function () {
    $('.mb-account-icon').click(function (e) {
      if ($('.account-sides-wrap').length) {
        e.preventDefault();
      }
      $('.account-sides-wrap').addClass('active');
    })

    $('.mb-header .btn-close').click(function (e) {
      $('.account-sides-wrap').removeClass('active');
    })

    $(document).click(function (e) {
      if (!$(e.target).closest('.account-sides-info').length && !$(e.target).closest('.mb-account-icon').length) {
        $('.account-sides-wrap').removeClass('active');
      }
    })
  }());

  // PC/MB 头部滑动固定
  (function () {
    const pbHeader = $(window).width() > 768 ? $('.header-content') : $('.header-mobile');
    if (!pbHeader.length) return;
    const headerContentTop = pbHeader.offset().top;
    const headerContentHeight = pbHeader.outerHeight(true);
    $(window).scroll(function () {
      const scrollTop = $(this).scrollTop();
      if (scrollTop > headerContentTop) {
        pbHeader.addClass('fixed');
        if (!$('.header-content-placeholder').length)
          pbHeader.before('<div class="header-content-placeholder" style="height: ' + headerContentHeight + 'px"></div>');
      } else {
        pbHeader.removeClass('fixed');
        $('.header-content-placeholder').remove();
      }
    })
  }());
});

