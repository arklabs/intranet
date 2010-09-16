(function($)
{
  $(function() {
    // admin
      $('#event_admin_form_client_id').fcbkcomplete($.extend({
        json_url: getJsonUrl(),
        cache: false,
        filter_case: false,
        filter_hide: true,
        newel: false,
        maxitems: "0",
        firstselected: true
      }, $(this).metadata()));
  });
  
})(jQuery);