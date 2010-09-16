function initButtonMenus(){
  $('.fg-button').hover(
          function(){ $(this).removeClass('ui-state-default').addClass('ui-state-focus'); },
          function(){ $(this).removeClass('ui-state-focus').addClass('ui-state-default'); }
  );
  $('.bt-flat').each(function(){
     $(this).menu({
        content: $(this).next().html(),  //grab content from this page
        showSpeed: 400
     });
  });
}
$(document).ready(function(){
   initButtonMenus();
});