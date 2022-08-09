// offcanvas-search-top
$(function() {
  var myOffcanvas = document.getElementById('offcanvas-search-top')
  myOffcanvas.addEventListener('shown.bs.offcanvas', function () {
    $('#offcanvas-search-top input').focus();
    $('#offcanvas-search-top input').keydown(function (e) {
      if (e.keyCode == 13) {
        console.log('enter');
        $('#offcanvas-search-top .btn-search').click();
      }
    })
  })
});