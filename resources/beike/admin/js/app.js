$(document).ready(function ($) {
  $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    beforeSend: function() { layer.load(2, {shade: [0.3,'#fff'] }); },
    complete: function() { layer.closeAll('loading'); },
    error: function(xhr, ajaxOptions, thrownError) {
      if (xhr.responseJSON.message) {
        layer.msg(xhr.responseJSON.message,() => {})
      }
    },
  });
});
