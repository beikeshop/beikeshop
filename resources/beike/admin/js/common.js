export default {
   fileManagerIframe(callback) {
    const base = document.querySelector('base').href;

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
          callback(images);
        }
      }
    });
  },

  debounce(fn, delay) {
    var timeout = null; // 创建一个标记用来存放定时器的返回值

    return function (e) {
      // 每当用户输入的时候把前一个 setTimeout clear 掉
      clearTimeout(timeout);
      // 然后又创建一个新的 setTimeout, 这样就能保证interval 间隔内如果时间持续触发，就不会执行 fn 函数
      timeout = setTimeout(() => {
          fn.apply(this, arguments);
      }, delay);
    }
  },

  autocomplete() {
    $.fn.autocomplete = function(option) {
      return this.each(function() {
        this.timer = null;
        this.items = new Array();

        $.extend(this, option);

        $(this).attr('autocomplete', 'off');

        // Focus
        $(this).on('focus', function() {
          this.request();
        });

        // Blur
        $(this).on('blur', function() {
          setTimeout(function(object) {
            object.hide();
          }, 200, this);
        });

        // Keydown
        $(this).on('keydown', function(event) {
          switch(event.keyCode) {
            case 27: // escape
              this.hide();
              break;
            default:
              this.request();
              break;
          }
        });

        // Click
        this.click = function(event) {
          event.preventDefault();

          let value = $(event.target).parent().attr('data-value');

          if (value && this.items[value]) {
            this.select(this.items[value]);
          }
        }

        // Show
        this.show = function() {
          var pos = $(this).position();

          $(this).siblings('ul.dropdown-menu').css({
            top: pos.top + $(this).outerHeight(),
            left: pos.left
          });

          $(this).siblings('ul.dropdown-menu').show();
        }

        // Hide
        this.hide = function() {
          $(this).siblings('ul.dropdown-menu').hide();
        }

        // Request
        this.request = function() {
          clearTimeout(this.timer);

          this.timer = setTimeout(function(object) {
            object.source($(object).val(), $.proxy(object.response, object));
          }, 200, this);
        }

        // Response
        this.response = function(json) {
          let hasFocus = $(this).is(':focus');
          if (!hasFocus) return;

          var html = '';

          if (json.length) {
            for (var i = 0; i < json.length; i++) {
              this.items[json[i]['value']] = json[i];
            }

            for (var i = 0; i < json.length; i++) {
              if (!json[i]['category']) {
                html += '<li data-value="' + json[i]['value'] + '"><a href="#" class="dropdown-item">' + json[i]['label'] + '</a></li>';
              }
            }

            // Get all the ones with a categories
            var category = new Array();

            for (var i = 0; i < json.length; i++) {
              if (json[i]['category']) {
                if (!category[json[i]['category']]) {
                  category[json[i]['category']] = new Array();
                  category[json[i]['category']]['name'] = json[i]['category'];
                  category[json[i]['category']]['item'] = new Array();
                }

                category[json[i]['category']]['item'].push(json[i]);
              }
            }

            for (var i in category) {
              html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

              for (j = 0; j < category[i]['item'].length; j++) {
                html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
              }
            }
          }

          if (html) {
            this.show();
          } else {
            this.hide();
          }

          $(this).siblings('ul.dropdown-menu').html(html);
        }

        $(this).after('<ul class="dropdown-menu"></ul>');
        $(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));
      });
    }
  },
}