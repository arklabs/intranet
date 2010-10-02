
<?php


use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');
use_stylesheet('fg-menu');
use_stylesheet('fb-buttons-menu');
use_javascript('lib.dataTable');
use_javascript('fg-menu');
use_javascript('fg-menu-handling');
use_helper('Date');

$table = _table('.data_table')->head(
  __('Origen'),
  __('Nombre'),
  __('Cell'),
  __('Casa'),
  __('Agente'),
  __('Propiedad'),
  __('Comments'),
  __('Fecha')
);

foreach ($incommingCallPager as $incommingCall)
{
  $date = new sfDate($incommingCall->getCreatedAt());
  if ($incommingCall->getPropertyId()){
      $property = Doctrine::getTable('PropertyList')->findById($incommingCall->getPropertyId());
      if (count($property) == 0) $property = '-';
      else {
          $property =  $property[0];
          $property = sprintf('<a  href="%s" class="color-box-trigger"> %s </a>', _link('app:admin/+/'.dmString::modulize($property->getType().'/edit'))->params(array('pk'=>$property->getId(), 'dm_embed'=>1))->getHref(), $property);
      }
  }
  else 
    $property = '-';

  if ($incommingCall->getAgentId()){
      $agent = Doctrine::getTable('Agent')->findById($incommingCall->getAgentId());
      if (count($agent) == 0) $agent = '-';
      else
          $agent = $agent[0];
  }else
      $agent = '-';
  
  
  $source = str_replace('IncommingCall', '', $incommingCall->getType());
  $source = str_replace('Inventrary', 'Inventario',$source);
  $source = str_replace('Prospect', 'Prospecto', $source);
  $table->body(
  $source,
  sprintf('<a  href="%s" class="color-box-trigger"> %s </a>', _link('app:admin/+/'.dmString::modulize($incommingCall->getType().'/edit'))->params(array('pk'=>$incommingCall->getId(), 'dm_embed'=>1))->getHref(), $incommingCall->getFirstName().' '.$incommingCall->getLastName()),
  $incommingCall->getCel(),
  $incommingCall->getHomePhone(),
  $agent,
  $property,
  $incommingCall->getComments(),
  format_date($date->dump(), ($date->getHour()!= 0)?'MMM d, y h:m a':'MMM d, y','en')
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
        $('.dataTables_paginate.fg-buttonset > .fg-button, .dataTables_paginate.fg-buttonset > span >.fg-button').click(function(){
            $('a[rel=tipsy]').tipsy({fade: true, gravity: 'n'});
        });
        $('.color-box-trigger').click(function(){
            parent.$.fn.colorbox({href: $(this).attr('href'), width:"70%", height:"80%", iframe:true, "css": ["/dmCorePlugin/lib/colorbox/theme3/colorbox.css"],"js":["/dmCorePlugin/lib/colorbox/jquery.colorbox.min.js"]});
            return false;
          });
   };

   function reload(){
       $('#loader').show();
       $('.incomming_call_list > .dm_widget_inner').load('/index.php/+/main/renderComponent?mod=incommingCall&name=list',{},function(){
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
	        aaSorting: [ [4, "desc"], [1, "asc"] ],
		  aoColumns: [
					      {"bSortable": true},
					      {"bSortable": true},
					      {"bSortable": false},
					      {"bSortable": true},
                                              {"bSortable": true},
                                              {"bSortable": true},
                                              {"bSortable": false},
                                              {"bSortable": true, "sType": "date"},
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