/*
 * @copyright     2022 opencart.cn - All Rights Reserved.
 * @link          https://www.guangdawangluo.com
 * @Author        PS <pushuo@opencart.cn>
 * @Date          2022-08-04 17:22:54
 * @LastEditTime  2022-08-09 09:40:08
 */
export default {
  /**
   * @description: 获取购物车数据
   * @return {*}
   */
  getCarts() {
    $http.get('carts/mini', null, {hload: true}).then((res) => {
      $('.offcanvas-right-cart-amount').html(res.data.amount_format);

      if (res.data.carts.length) {
        $('.navbar-icon-link-badge').html(res.data.carts.length > 99 ? '99+' : res.data.carts.length).show();
        $('.offcanvas-right-cart-count').html(res.data.quantity);

        let html = '';
        res.data.carts.forEach(e => {
          html += '<div class="product-list d-flex align-items-center">';
            html += `<div class="left"><img src="${e.image}" calss="img-fluid"></div>`;
            html += '<div class="right flex-grow-1">';
              html += `<div class="name fs-sm fw-bold mb-2">${e.name}</div>`;
              html += '<div class="product-bottom d-flex justify-content-between align-items-center">';
                html += `<div class="price">${e.price_format} <span class="text-muted">x ${e.quantity}<span></div>`;
                html += `<span class="offcanvas-products-delete" data-id="${e.cart_id}"><i class="bi bi-x-lg"></i> 删除</span>`;
              html += '</div>';
            html += '</div>';
          html += '</div>';
        })

        $('.offcanvas-right-products').html(html)
      }
    })
  },

  /**
   * @description: 加入购物车
   * @param {*} sku_id  商品id
   * @param {*} quantity  商品数量
   * @param {*} isBuyNow  是否立即购买
   * @return {*}  返回Promise
   */
  addCart({sku_id, quantity = 1, isBuyNow = false}, event) {
    const $btn = $(event);
    const btnHtml = $btn.html();
    const loadHtml = '<span class="spinner-border spinner-border-sm"></span>';
    $btn.html(loadHtml).prop('disabled', true);

    $http.post('/carts', {sku_id, quantity}, {hload: !!event}).then((res) => {
      this.getCarts();
      layer.msg(res.message)
      if (isBuyNow) {
        location.href = 'checkout'
      }
    }).finally(() => {$btn.html(btnHtml).prop('disabled', false)})
  },

  addWishlist(id, event) {
    const $btn = $(event);
    const btnHtml = $btn.html();
    const isWishlist = $btn.attr('data-in-wishlist') * 1;
    const loadHtml = '<span class="spinner-border spinner-border-sm"></span>';

    if (isWishlist) {
      $btn.html(loadHtml).prop('disabled', true);
      $http.delete(`account/wishlist/${isWishlist}`, null, {hload: true}).then((res) => {
        layer.msg(res.message)
        $btn.attr('data-in-wishlist', '0');
      }).finally((e) => {
        $btn.html(btnHtml).prop('disabled', false).find('i.bi').prop('class', 'bi bi-heart')
      })
    } else {
      $btn.html(loadHtml).prop('disabled', true);
      $http.post('account/wishlist', {product_id: id}, {hload: true}).then((res) => {
        layer.msg(res.message)
        $btn.attr('data-in-wishlist', res.data.id);
        $btn.html(btnHtml).prop('disabled', false).find('i.bi').prop('class', 'bi bi-heart-fill')
      }).catch((e) => {
        $btn.html(btnHtml).prop('disabled', false)
      })
    }
  },

  /**
   * @description: 滑动固定顶部
   * @return {*}
   */
  slidingFixed() {
    $(document).ready(() => {
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
      })
    })
  }
}