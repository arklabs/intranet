
<?php
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');
use_stylesheet('fg-menu');
use_stylesheet('fb-buttons-menu');
use_javascript('lib.dataTable');
use_javascript('fg-menu');
use_javascript('fg-menu-handling');
use_helper('Date');
$sfModule = 'externalAgent';

$table = _table('.data_table')->head(
  __('Realizada por un'),
  __('Nombre'),
  __('Tel&eacute;fono'),
  __('Raz&oacute;n'),
  __('Realizada')
);

foreach ($incommingCallPager as $incommingCall)
{
  $date = new sfDate($incommingCall->getCreatedAt());
  $table->body(
  $incommingCall->getDmUser()->getType(),
  $incommingCall->getDmUser(),
  ($incommingCall->getDmUser()->getPhone())?sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.dmString::modulize($incommingCall->getType()).'/edit')->params(array('pk'=> $incommingCall->getDmUser()->getId(),'dm_embed'  => 1))->getHref(), 'Clic para modificar el teléfono', $incommingCall->getDmUser()->getPhone()):sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.dmString::modulize($incommingCall->getDmUser()->getType()).'/edit')->params(array('pk'=> $incommingCall->getDmUser()->getId(),'dm_embed'  => 1))->getHref(), 'Clic para agregar el teléfono', "No asignado"),
  sprintf('<a rel="tipsy" title="%s" href="%s"> %s </a>',$incommingCall->getDescription(),'#',$incommingCall->getIncommingCallReason()),
  format_date($date->dump(), ($date->getHour()!= 0)?'MMM d, y h:m a':'MMM d, y','en')
  );
}
//echo _open('form', array('method'=>'POST', 'action'=>'/+/event/batchChangeStatus', 'name'=>'eventListForm'));
echo $table;
//echo _close('form');
?>
<script type="text/javascript">
   var prepend = '<?php include_partial('externalAgent/listHeader', array( 'sfModule'=>'externalAgent', 'statusControl'=>''));?>';
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