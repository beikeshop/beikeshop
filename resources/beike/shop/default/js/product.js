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