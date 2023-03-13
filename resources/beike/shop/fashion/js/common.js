/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-09-09 19:16:39
 * @LastEditTime  2023-03-12 21:16:10
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
        $('.cart-badge-quantity').show().html(res.data.quantity_all > 99 ? '99+' : res.data.quantity_all);
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
    if (!config.isLogin && !config.guestCheckout) {
      this.openLogin()
      return;
    }

    const $btn = $(event);
    const btnHtml = $btn.html();
    const loadHtml = '<span class="spinner-border spinner-border-sm"></span>';
    $btn.html(loadHtml).prop('disabled', true);
    $(document).find('.tooltip').remove();

    $http.post('/carts', {sku_id, quantity, buy_now: isBuyNow}, {hload: !!event}).then((res) => {
      this.getCarts();
      layer.msg(res.message)
      if (isBuyNow) {
        location.href = 'checkout'
      }
    }).finally(() => {$btn.html(btnHtml).prop('disabled', false)})
  },

  addWishlist(id, event) {
    if (!config.isLogin) {
      this.openLogin()
      return;
    }

    const $btn = $(event);
    const btnHtml = $btn.html();
    const isWishlist = $btn.attr('data-in-wishlist') * 1;
    const loadHtml = '<span class="spinner-border spinner-border-sm"></span>';
    $(document).find('.tooltip').remove();

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

  getQueryString(name, defaultValue) {
    const reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)');
    const r = window.location.search.substr(1).match(reg);
    if (r != null) {
      return decodeURIComponent(r[2]);
    }

    return typeof(defaultValue) != 'undefined' ? defaultValue : '';
  },

  removeURLParameters(url, ...parameters) {
    const parsed = new URL(url);
    parameters.forEach(e => parsed.searchParams.delete(e))
    return parsed.toString()
  },

  updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
      return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
      return uri + separator + key + "=" + value;
    }
  },

  openWin(url, name = '', iWidth = 700, iHeight = 500) {
    var iTop = (window.screen.height - 30 - iHeight) / 2;
    var iLeft = (window.screen.width - 10 - iWidth) / 2;
    window.open(url, name, 'height=' + iHeight + ',innerHeight=' + iHeight
    + ',width=' + iWidth + ',innerWidth=' + iWidth + ',top=' + iTop + ',left=' + iLeft
    + ',toolbar=no,menubar=no,scrollbars=auto,resizeable=no,location=no,status=no');
  },
}
