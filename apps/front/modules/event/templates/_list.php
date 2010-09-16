<?php
use_javascript('lib.dataTable');
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');
use_helper('Date');

// Plugin : List
// Vars : $pluginPager
$sfModule = 'event';

$table = _table('.data_table')->head(
  _tag('input', array('type'=>'checkbox', 'class'=>'check-all')),
  __('Titulo'),
  __('Estado'),
  __('Categoria'),
  __('Fecha Inicio'),
  __('Fecha Fin'),
  __('Creado Por')
);

foreach ($eventPager as $event)
{
  $date_start = new sfDate($event->getDateStart());
  $date_end = new sfDate($event->getDateEnd());
  $table->body(
  _tag('input', array('type'=>'checkbox', 'value'=>$event->getId(), 'name'=>'ids[]')),
  sprintf('<a href="%s" rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $event->getId(),'dm_embed'  => 1))->getHref(), $event->getDescription(), $event->getTitle()),
  $event->getEventStatus(),
  $event->getEventCategory(),
  format_date($date_start->dump(), ($date_start->getHour()!= 0)?'MMM d, y h:m a':'MMM d, y','en'),
  format_date($date_end->dump(), ($date_end->getHour()!= 0)?'MMM d, y h:m a':'MMM d, y','en'),
  $event->CreatedBy
  );
}
echo _open('form', array('method'=>'POST', 'action'=>_link('app:front/+/event/batchChangeStatus')->getHref(), 'name'=>'eventListForm'));
echo $table;
echo _close('form');
?>
<script type="text/javascript">
   var prepend = '<?php include_partial('event/listHeader', array('availableStatus'=>$availableStatus, 'sfModule'=>'event', ));?>';
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
                $("form[name='eventListForm']").submit();
            }
            else {
                alert('Debe seleccionar un evento y un estado');
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
       $('.event_list > .dm_widget_inner').load('/index.php/+/main/renderComponent?mod=event&name=list',{},function(){
            //initDatatable();
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
        aaSorting: [ [5, "asc"], [6, "asc"],[2, "asc"] ],
	aoColumns: [
		      {"bSortable": false},
		      {"bSortable": true, "sClass": "title-col"},
		      null,
		      null,
		      {"bSortable": true, "sType": "date"},
		      {"bSortable": true, "sType": "date"},
		      null,
		   ]
      });
       $('th.ui-state-default:first').css('min-width', '1.5em');
       $('th.ui-state-default:first').css('width', '1.5em');
       $('.data_table').css('width', '100%');
       // building status list
       loadControls();
	}
  }
  
  $(document).ready(function(){
	initDatatable();
  });



</script>
