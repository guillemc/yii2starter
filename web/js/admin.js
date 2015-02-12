$(function() {

$('a[rel="external"]').attr('target', '_blank');

$('body').on('click', 'a[data-pager]', function () {
  var $el = $(this),
    size = $el.data('pager'),
    grid = $el.data('rel') || '#main-grid',
    $grid = $(grid);

  $grid.find('tr.filters td').last().append('<input type="hidden" name="page_size" value="'+size+'" />');
  $el.parents('ul').siblings('.dropdown-toggle').dropdown('toggle');

  $grid.yiiGridView('applyFilter');

  return false;

});

$('body').on('click', 'a[data-action]', function () {
  var $el = $(this),
    action = $el.data('action');

  if (action == 'back') {
    if (!document.referrer) return true;

    history.back();
    return false;
  }

  return true;

});


});