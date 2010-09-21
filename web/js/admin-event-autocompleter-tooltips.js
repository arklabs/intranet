$(document).ready(function(){
	$('#autocomplete_event_admin_form_phraseology_id').tipsy({
		html: true,
		title: function() {
			$('#autocomplete_event_admin_form_phraseology_id').addClass('ac_loading');
			if ($('#autocomplete_event_admin_form_phraseology_id').val() ==''){
				$('#autocomplete_event_admin_form_phraseology_id').removeClass('ac_loading');
				return 'Escriba una palabra para recomendarle faseolog&iacute;as';
			}	
			return $.ajax({ 
				   type: "GET",
				   url: "/admin.php/+/event/getPhraseologyContent/", 
				   data: "phraseology-id="+$('input#event_admin_form_phraseology_id').attr('value'),
				   async: false,
				   complete: function(){
						$('#autocomplete_event_admin_form_phraseology_id').removeClass('ac_loading');
				   }
			 }).responseText;

		} 
	});
	$('#autocomplete_event_admin_form_client_id').tipsy({
		html: true,
		title: function(){
			return "Escriba el nombre o el apellido del cliente para listar clientes coincidentes";
		}
	});
		
	
});