$(document).ready(function(){
	$('.error_list > li').each(function(){
		$(this).html('<a class="link">'+$(this).html()+'</a>')
	});
});