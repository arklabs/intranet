$(document).ready(function(){
    $('.mapLauncher').click(function(){
        $('#loader').show();
        $('.dm_google_map').parent().load($(this).attr('href'), {}, function(){
			$('.dm_google_map').dmGoogleMap();
            $('#loader').hide();
        });
		return false;
    });
});