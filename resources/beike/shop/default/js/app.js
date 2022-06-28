$(document).ready(function ($) {
  $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });

  $('.quantity-wrap .right i').on('click', function(event) {
    let input = $(this).parent().siblings('input')

    if ($(this).hasClass('bi-chevron-up')) {
      input.val(input.val() * 1 + 1)
      return;
    }

    if (input.val() * 1 <= input.attr('minimum') * 1) {
      return;
    }

    input.val(input.val() * 1 - 1)
  });
});
