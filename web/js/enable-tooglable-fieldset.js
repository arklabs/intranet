function enableToogableFieldsets(){
	$('h2.fieldset_title').each(function(){
		$(this).css('cursor','pointer');
	});
        
	$('h2.fieldset_title').click(function(){
                
		$(this).next().slideToggle("fast");
		var title = $(this).find('>span.ui-icon');
		if (title.hasClass('ui-icon-triangle-1-e')){
			title.removeClass('ui-icon-triangle-1-e');
			title.addClass('ui-icon-triangle-1-s');
		}
		else {
			title.removeClass('ui-icon-triangle-1-s');
			title.addClass('ui-icon-triangle-1-e');
		}
	});
}
$(document).ready(function(){
	enableToogableFieldsets();
});	