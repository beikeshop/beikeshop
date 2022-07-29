import http from "../../../../js/http";
window.$http = http;

// 创建 bk 对象
window.bk = window.bk || {};

$(document).ready(function ($) {
  $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    beforeSend: function() { layer.load(2, {shade: [0.3,'#fff'] }); },
    complete: function() { layer.closeAll('loading'); },
  });

  bk.getCarts();

  $(document).on('click', '.offcanvas-products-delete', function(event) {
    const $this = $(this)
    const cartId = $(this).data('id')

    $http.delete(`/carts/${cartId}`).then((res) => {
      $this.parents('.product-list').remove()
    })
  })

  $(document).on('click', '.quantity-wrap .right i', function(event) {
    event.stopPropagation();
    let input = $(this).parent().siblings('input')

    if ($(this).hasClass('bi-chevron-up')) {
      input.val(input.val() * 1 + 1)
      input.get(0).dispatchEvent(new Event('input'));
      return;
    }

    if (input.val() * 1 <= input.attr('minimum') * 1) {
      return;
    }

    input.val(input.val() * 1 - 1)
    input.get(0).dispatchEvent(new Event('input'));
  });

  // 滑动固定顶部
  (function ($) {
    if (!$('.fixed-top-line').length) return;
    if ($(window).width() < 768) return;
    const totalWrapTop = $('.fixed-top-line').offset().top;
    const totalWrapWidth = $('.fixed-top-line').outerWidth();
    const totalWrapHeight = $('.fixed-top-line').outerHeight();
    const totalWrapLeft = $('.fixed-top-line').offset().left;
    const footerTop = $('footer').offset().top;
    const footerMarginTop = Math.abs(parseInt($('footer').css("marginTop")));

    $(window).scroll(function () {
      if ($(this).scrollTop() > totalWrapTop) {
        $('.fixed-top-line').css({position: 'fixed', top: 0, bottom: 'auto', 'width': totalWrapWidth})
        if (!$('.total-old').length) {
          $('.fixed-top-line').before('<div class="total-old" style="height:' + totalWrapHeight + 'px; width:100%;"></div>');
        }

        if ($(this).scrollTop() + totalWrapHeight > footerTop - footerMarginTop) {
          $('.fixed-top-line').css({position: 'absolute', top: 'auto', bottom: '0', 'width': totalWrapWidth})
        }
      } else {
        $('.total-old').remove();
        $('.fixed-top-line').removeAttr('style')
      }


      // if ($(this).scrollTop() > totalWrapTop) {
      //   $('.fixed-top-line').addClass('fixed-top-line-fixed').css({'left': totalWrapLeft, 'width': totalWrapWidth})
      //   if ($('.total-old').length > 0) return;
      //   $('.fixed-top-line').before('<div class="total-old" style="height:' + totalWrapHeight + 'px; width:100%;"></div>');
      // } else {
      //   $('.total-old').remove();
      //   $('.fixed-top-line').removeClass('fixed-top-line-fixed').css({'left': 0, 'width': 'auto'})
      // }
    })
  })(window.jQuery);
});

bk.getCarts = function () {
  $http.get('carts/mini', null, {hload: true}).then((res) => {
    $('.offcanvas-right-cart-amount').html(res.data.amount_format);

    if (res.data.carts.length) {
      $('.navbar-icon-link-badge').html(res.data.carts.length > 99 ? '99+' : res.data.carts.length).show();
      $('.offcanvas-right-cart-count').html(res.data.carts.length);

      let html = '';
      res.data.carts.forEach(e => {
        html += '<div class="product-list d-flex align-items-center">';
          html += `<div class="left"><img src="${e.image}" calss="img-fluid"></div>`;
          html += '<div class="right flex-grow-1">';
            html += `<div class="name fs-sm fw-bold mb-2">${e.name}</div>`;
            html += '<div class="product-bottom d-flex justify-content-between align-items-center">';
              html += `<div class="price">${e.price_format}</div>`;
              html += `<span class="offcanvas-products-delete" data-id="${e.cart_id}"><i class="bi bi-x-lg"></i> 删除</span>`;
            html += '</div>';
          html += '</div>';
        html += '</div>';
      })

      $('.offcanvas-right-products').html(html)
    }
  })
}