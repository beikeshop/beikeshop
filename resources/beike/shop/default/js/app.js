$(document).ready(function ($) {
  $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    beforeSend: function() { layer.load(2, {shade: [0.3,'#fff'] }); },
    complete: function() { layer.closeAll('loading'); },
  });

  $('.quantity-wrap .right i').on('click', function(event) {
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

    $(window).scroll(function () {
      if ($(this).scrollTop() > totalWrapTop) {
        $('.fixed-top-line').addClass('fixed-top-line-fixed').css({'left': totalWrapLeft, 'width': totalWrapWidth})
        if ($('.total-old').length > 0) return;
        $('.fixed-top-line').before('<div class="total-old" style="height:' + totalWrapHeight + 'px; width:100%;"></div>');
      } else {
        $('.total-old').remove();
        $('.fixed-top-line').removeClass('fixed-top-line-fixed').css({'left': 0, 'width': 'auto'})
      }
    })
  })(window.jQuery);
});
