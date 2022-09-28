/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-09-09 19:16:39
 * @LastEditTime  2022-09-28 17:23:48
 */

export default {
  /**
   * @description: 获取购物车数据
   * @return {*}
   */
  getCarts() {
    $(document).ready(() => {
      $http.get('carts/mini', null, {hload: true}).then((res) => {
        $('#offcanvas-right-cart').html(res.data.html);
        if (!res.data.quantity) {
          $('.cart-badge-quantity').hide();
        } else {
          $('.cart-badge-quantity').show().html(res.data.quantity > 99 ? '99+' : res.data.quantity);
        }
      })
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
    if (!isLogin) {
      this.openLogin()
      return;
    }

    const $btn = $(event);
    const btnHtml = $btn.html();
    const loadHtml = '<span class="spinner-border spinner-border-sm"></span>';
    $btn.html(loadHtml).prop('disabled', true);

    $http.post('/carts', {sku_id, quantity, buy_now: isBuyNow}, {hload: !!event}).then((res) => {
      this.getCarts();
      layer.msg(res.message)
      if (isBuyNow) {
        location.href = 'checkout'
      }
    }).finally(() => {$btn.html(btnHtml).prop('disabled', false)})
  },

  addWishlist(id, event) {
    if (!isLogin) {
      this.openLogin()
      return;
    }

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
  },

  openLogin() {
    layer.open({
      type: 2,
      title: '',
      shadeClose: true,
      scrollbar: false,
      area: ['900px', '600px'],
      skin: 'login-pop-box',
      content: 'login?iframe=true' //iframe的url
    });
  },

  openWin(url, name = '', iWidth = 700, iHeight = 500) {
    var iTop = (window.screen.height - 30 - iHeight) / 2;;
    var iLeft = (window.screen.width - 10 - iWidth) / 2;;
    window.open(url, name, 'height=' + iHeight + ',innerHeight=' + iHeight
　　　　+ ',width=' + iWidth + ',innerWidth=' + iWidth + ',top=' + iTop + ',left=' + iLeft
　　　　+ ',toolbar=no,menubar=no,scrollbars=auto,resizeable=no,location=no,status=no');
  }
}