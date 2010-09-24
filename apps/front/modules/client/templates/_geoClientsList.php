<?php
use_stylesheet('fb-buttons-menu');
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');
use_stylesheet('fg-menu');
use_javascript('lib.dataTable');
use_javascript('geolocation-handler');
use_javascript('fg-menu');
use_javascript('fg-menu-handling');
use_helper('DmGoogleMap');

$sfModule = 'client';

$table = _table('.data_table')->head(
  _tag('input', array('type'=>'checkbox', 'class'=>'check-all')),
  __('Nombre'),
  __('Dirección'),
  __('Teléfono'),
  __('Trámites')
);

foreach ($clientPager as $client)
{
   //print_r($client->getLiveIn());die;
  
  $table->body(
  _tag('input', array('type'=>'checkbox', 'value'=>$client->getId(), 'name'=>'ids[]')),
  ($client->getHouse())?sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="mapLauncher"> %s</a>',_link('app:front/+/main/renderGeoLocation')->params(array('address'=> urlencode($client->getHouse())))->getHref(), 'Clic para geolocalizar este cliente', $client->getFirstName().' '.$client->getLastName()):sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $client->getId(),'dm_embed'  => 1))->getHref(), 'Este cliente no tiene dirección asignada. No se puede geolocalizar. Haga clic en Dirección para crear una nueva dirección y luego clic aqui para asignar la dirección creada a este cliente.', $client->getFirstName().' '.$client->getLastName()),
  ($client->getLiveIn())?sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/house/edit')->params(array('dm_embed'=>1, 'pk'=>$client->getHouse()->getId()))->getHref(), 'Clic para editar esta dirección', $client->getHouse()):sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/house/new')->params(array('dm_embed'=> 1))->getHref(), 'Clic para agregar la vivienda de este cliente', "No asignado"),
  ($client->getPhone())?sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $client->getId(),'dm_embed'  => 1))->getHref(), 'Clic para modificar el teléfono de este cliente', $client->getPhone()):sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $client->getId(),'dm_embed'  => 1))->getHref(), 'Clic para agregar el teléfono de este cliente', "No asignado"),
  get_partial('client/clientFilesCell', array('client'=>$client))
  );
}
//echo _open('form', array('method'=>'POST', 'action'=>'/+/event/batchChangeStatus', 'name'=>'eventListForm'));
echo $table;
//echo _close('form');
?>
<script type="text/javascript">
   var prepend = '';
   var append = '';
   
   function loadControls(){ // this js function is called on front.js after datatable loads.
        $('.dataTables_length').prepend(prepend); // put html rendered in partial before "show n entries" control
        $('.dataTables_length').append(append); // put html rendered in partial after "show n entries" control
        $('a[rel=tipsy]').tipsy({fade: true, gravity: 'n'});

        // other js handling with datatable content here.
        $('a.button#new').click(function(){
          parent.$.fn.colorbox({href: $(this).attr('href'), width:"70%", height:"80%", iframe:true, "css": ["/dmCorePlugin/lib/colorbox/theme3/colorbox.css"],"js":["/dmCorePlugin/lib/colorbox/jquery.colorbox.min.js"]});
          return false
       });
       $('a.button#apply').click(function(){
            if ($("select[name='status']>option:selected").val()!= '-1' && $("input[type='checkbox']:checked").length > 0){
                $("form[name='clientListForm']").submit();
            }
            else {
                alert('Debe seleccionar un cliente y un estado!!!!!!!');
            }
        });
        $('.dataTables_paginate.fg-buttonset > .fg-button, .dataTables_paginate.fg-buttonset > span >.fg-button').click(function(){
            $('a[rel=tipsy]').tipsy({fade: true, gravity: 'n'});
        });
       
       $('.check-all').click(
                function(){
                        $(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));
                }
        );
   };

   function reload(){
       $('#loader').show();
       $('.client_geo_clients_list > .dm_widget_inner').load('/index.php/+/main/renderComponent?mod=client&name=geoClientsList',{},function(){
            //initDatatable();
			enableToogableFieldsets();
            $('#loader').hide();
            initButtonMenus();
            loadNotifications();
             $('.color-box-trigger').click(function(){
                   parent.$.fn.colorbox({href: $(this).attr('href'), width:"70%", height:"80%", iframe:true, "css": ["/dmCorePlugin/lib/colorbox/theme3/colorbox.css"],"js":["/dmCorePlugin/lib/colorbox/jquery.colorbox.min.js"]});
                   return false;
               });
       });
       //location.reload(); // to reload the whole page
   }
   function initDatatable(){
	    if($dataTable = $('table.data_table').orNot())
	    {
	      $dataTable.dataTable({
	        bJQueryUI: true,
	        bPaginate: true,
	        sPaginationType: "full_numbers",
	        aaSorting: [ [1, "asc"], [2, "asc"] ],
		  aoColumns: [
					      {"bSortable": false},
					      {"bSortable": true, "sClass": "title-col"},
					      null,
					      null,
					      {"bSortable": false},
					 ]
	      });
	    $('th.ui-state-default:first').css('max-width', '2%');
	    $('th.ui-state-default:first').css('width', '2%');
	    $('.data_table').css('width', '100%');
	    // building status list
	    loadControls();
	   }
	}
	$(document).ready(function(){
		initDatatable();
	});
</script>