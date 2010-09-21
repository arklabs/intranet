(function($)
{
  // front
  $('#dm_page div.dm_widget').bind('dmWidgetLaunch', function()
  {
    var $map = $(this).find('.dm_google_map');

    if($map.length)
    {
      setTimeout(function() {$map.dmGoogleMap();}, 1000);
    }
  });

  // admin
  $(function() {
    $('#dm_admin_content .dm_google_map').each(function() {
      $(this).dmGoogleMap();
    });
  });
  
})(jQuery);