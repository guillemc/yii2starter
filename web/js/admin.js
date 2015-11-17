$(function() {

$('a[rel="external"]').attr('target', '_blank');


var $body = $('body');

$body.on('click', '[data-toggle="offcanvas"]', function (el) {
  var d = new Date();
  d.setTime(d.getTime()+(24*3600*1000));
  if ($('body').hasClass('sidebar-collapse')) {
    document.cookie = "admin-sidebar-collapse=1;path=/;expires="+d.toUTCString();
  } else {
    document.cookie = "admin-sidebar-collapse=;path=/;expires="+d.toUTCString();
  }
});

$body.on('click', 'a[data-pager]', function () {
  var $el = $(this),
    size = $el.data('pager'),
    grid = $el.data('rel') || '#main-grid',
    $grid = $(grid);

  $grid.find('tr.filters td').last().append('<input type="hidden" name="page_size" value="'+size+'" />');
  $el.parents('ul').siblings('.dropdown-toggle').dropdown('toggle');

  $grid.yiiGridView('applyFilter');

  return false;

});

$body.on('click', 'a[data-action]', function () {
  var $el = $(this),
    action = $el.data('action');

  if (action == 'back') {

    if (!document.referrer) return true;
    history.back();
    return false;

  } else if (action == 'delete') { //ajax delete and reload grid
      var cont = $el.parents('[data-pjax-container]'),
          msg = $el.data('confirm');
      if (!cont.length) return true;
      if (!msg || confirm(msg)) {
        $.ajax({
          url: $el.attr('href'),
          type: "post",
          error: function(xhr, status, error) {
            alert('Error: '+xhr.responseText);
          }
        }).done(function(data) {
          $.pjax.reload({container: '#'+cont.get(0).id, timeout: 3000});
        });
      }
      return false;
  }
  return true;
});


});