<?php
use_javascript('lib.dataTable');
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');
use_stylesheet('fb-buttons-menu');
use_helper('Date');

// Plugin : List
// Vars : $pluginPager
$sfModule = 'clientFile';

$table = _table('.data_table')->head(
  _tag('input', array('type'=>'checkbox', 'class'=>'check-all')),
  __('Cliente'),
  __('Tramite'),
  __('Departamento'),
  __('Fecha Inicio'),
  __('Fecha Fin'),
  __(" ")        
);

foreach ($clientFilePager as $clientFile)
{
  $date_start = new sfDate($clientFile->getDateStart());
  $date_end = new sfDate($clientFile->getDateEnd());
  $table->body(
  _tag('input', array('type'=>'checkbox', 'value'=>$clientFile->getId(), 'name'=>'ids[]')),
  sprintf('<a href="%s" rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $clientFile->getId(),'dm_embed'  => 1))->getHref(), $clientFile->getClient(), $clientFile->getClient()),
  $clientFile->getFileType(),
  $clientFile->getDepartment(),
  format_date($date_start->dump(), ($date_start->getHour()!= 0)?'MMM d, y h:m a':'MMM d, y','en'),
  format_date($date_end->dump(), ($date_end->getHour()!= 0)?'MMM d, y h:m a':'MMM d, y','en'),
  _open('a.color-box-trigger.fg-button.fg-button-icon-right.ui-widget.ui-state-default.ui-corner-all', array('tabindex'=>0, 'href'=>_link('app:admin/+/comment/new')->params(array('dm_embed'=>1, 'defaults[client_file_id]'=>$clientFile->getId()))->getHref())).
  _tag('span.ui-icon.ui-icon-plus',"")."Comentario"._close('a')
  );
}
//echo _open('form', array('method'=>'POST', 'action'=>'/+/clientFile/batchChangeStatus', 'name'=>'clientFileListForm'));
echo $table;
//echo _close('form');
?>
<script type="text/javascript">
   var prepend = '<?php include_partial('clientFile/listHeader', array( 'sfModule'=>'clientFile', 'statusControl'=>''));?>';
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
                $("form[name='clientFileListForm']").submit();
            }
            else {
                alert('Debe seleccionar un tramite y un estado');
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
       $('.client_file_list > .dm_widget_inner').load('/index.php/+/main/renderComponent?mod=clientFile&name=list',{},function(){
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
        aaSorting: [ [2, "asc"], [3, "asc"], [4, "asc"] ],
	aoColumns: [
		      {"bSortable": false },
		      {"bSortable": true, "sClass": "title-col"},
		      {"bSortable": true},
                      {"bSortable": true, "sType": "date"},
                      {"bSortable": true, "sType": "date"},
                      null,
                      {"bSortable": false },
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
