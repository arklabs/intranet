$(document).ready(function(){
	$('select#watchedSelectBox').change(function(){
		$('#loader').css('left', '50%');
		$('#event_admin_form_status_id').attr("disabled", "disabled"); 
		$('#loader').show();
		$.ajax({
			url: '/admin.php/+/event/fixStatusList',
			data: 'current-category='+$(this).find('> option:selected').val()+'&event-id='+$('#event_admin_form_id').attr('value'),
			type: 'GET',
			success: function(response){
				$('#event_admin_form_status_id').html(response);
			},
			complete: function(){
				$('#loader').hide();
				$('#event_admin_form_status_id').removeAttr("disabled");
                               
			},
			error: function(){
				$('#loader').hide();
			}
		});
	});
	$('select#watchedSelectBox').change();
});
