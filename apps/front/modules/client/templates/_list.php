
<?php
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');
use_stylesheet('fg-menu');
use_stylesheet('fb-buttons-menu');
use_javascript('lib.dataTable');
use_javascript('fg-menu');
use_javascript('fg-menu-handling');
$sfModule = 'client';

$table = _table('.data_table')->head(
  //_tag('input', array('type'=>'checkbox', 'class'=>'check-all')),
  __('Nombre'),
  __('Dirección'),
  __('Teléfono'),
  __('Tramites'),
  __('Acciones')
);

foreach ($clientPager as $client)
{
   //print_r($client->getLiveIn());die;
  
  $table->body(
  //_tag('input', array('type'=>'checkbox', 'value'=>$client->getId(), 'name'=>'ids[]')),
  sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $client->getId(),'dm_embed'  => 1))->getHref(), $client->getUsername(), $client->getFirstName().' '.$client->getLastName()),
  ($client->getLiveIn())?sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/house/edit')->params(array('dm_embed'=>1, 'pk'=>$client->getHouse()->getId()))->getHref(), 'Clic para editar esta dirección', $client->getHouse()):sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/house/new')->params(array('dm_embed'=> 1))->getHref(), 'Clic para agregar la vivienda de este cliente', "No asignado"),
  ($client->getPhone())?sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $client->getId(),'dm_embed'  => 1))->getHref(), 'Clic para modificar el teléfono de este cliente', $client->getPhone()):sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $client->getId(),'dm_embed'  => 1))->getHref(), 'Clic para agregar el teléfono de este cliente', "No asignado"),
  get_partial('client/clientFilesCell', array('client'=>$client)),
  _open('a.bt-flat.fg-button.fg-button-icon-right.ui-widget.ui-state-default.ui-corner-all', array('tabindex'=>0, 'href'=>'#')).
  _tag('span.ui-icon.ui-icon-carat-1-s',"")."Acciones"._close('a').
  _open('div.hidden').get_partial('client/clientActionsPanel', array('client'=>$client))._close('div')
  );
}
//echo _open('form', array('method'=>'POST', 'action'=>'/+/event/batchChangeStatus', 'name'=>'eventListForm'));
echo $table;
//echo _close('form');
?>
<script type="text/javascript">
   var prepend = '<?php include_partial('client/listHeader', array( 'sfModule'=>'client', 'statusControl'=>''));?>';
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
       $('.client_list > .dm_widget_inner').load('/index.php/+/main/renderComponent?mod=client&name=list',{},function(){
            //initDatatable();
	    initButtonMenus();
            $('#loader').hide();
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
	        aaSorting: [ [0, "asc"], [1, "asc"] ],
		  aoColumns: [
					      {"bSortable": true},
					      {"bSortable": true, "sClass": "title-col"},
					      {"bSortable": false},
					      {"bSortable": false},
					      {"bSortable": false},
					 ]
	      });
	    $('.data_table').css('width', '100%');
	    // building status list
	    loadControls();
	   }
	}

$(document).ready(function(){
        initDatatable();
});
</script>