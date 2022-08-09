/*
 * @copyright     2022 opencart.cn - All Rights Reserved.
 * @link          https://www.guangdawangluo.com
 * @Author        PS <pushuo@opencart.cn>
 * @Date          2022-08-09 09:39:34
 * @LastEditTime  2022-08-09 09:43:18
 */
export default {
   fileManagerIframe(callback) {
    const base = document.querySelector('base').href;
    const $this = $(this);

    layer.open({
      type: 2,
      title: '图片管理器',
      shadeClose: false,
      skin: 'file-manager-box',
      scrollbar: false,
      shade: 0.4,
      area: ['1060px', '680px'],
      content: `${base}/file_manager`,
      success: function(layerInstance, index) {
        var iframeWindow = window[layerInstance.find("iframe")[0]["name"]];
        iframeWindow.callback = function(images) {
          if (callback && typeof(callback) === "function") return callback(images);

          $this.find('img').prop('src', images[0].url);
          $this.next('input').val(images[0].path)
          $this.next('input')[0].dispatchEvent(new Event('input'));
        }
      }
    });
  },
}