<?php
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');
use_stylesheet('fg-menu');
use_stylesheet('fb-buttons-menu');
use_javascript('lib.dataTable');
use_javascript('fg-menu');
use_helper('Date');

$table = _table('.data_table')->head(
  //_tag('input', array('type'=>'checkbox', 'class'=>'check-all')),
  __('Cliente'),
  __('Propiedad'),
  __('Teléfono'),
  __('Fecha de llamada'),
  __('Tipo de Llamada')
);

foreach ($callCenterPager as $call)
{
  $table->body(
  sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/client/edit')->params(array('pk'=> $call->getClient()->getId(),'dm_embed'  => 1))->getHref(), 'Clic para ver detalles de este cliente', $call->getClient()->__toString()),
  ($call->getType()!= 'Referidos')?sprintf('<a href="%s"  rel="tipsy" original-title="Clic para editar esta propiedad" class="color-box-trigger"> %s</a>',_link('app:admin/+/property/edit')->params(array('dm_embed'=>1, 'pk'=>$call->getProperty()->getId()))->getHref(), $call->getProperty()->getHouse()->getAddress()):"",
  ($call->getClient()->getPhone())?sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/client/edit')->params(array('pk'=> $call->getClient()->getId(),'dm_embed'  => 1))->getHref(), 'Clic para modificar el teléfono de este cliente', $call->getClient()->getPhone()):sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/client/edit')->params(array('pk'=> $call->getClient()->getId(),'dm_embed'  => 1))->getHref(), 'Clic para agregar el teléfono de este cliente', "No asignado"),
  format_date($call->getCreatedAt(), 'MMM d, y h:m a','en'),
  sprintf('<a class="color-box-trigger" href="%s" rel="tipsy" original-title=" %s - Click para ver los detalles de esta llamada">%s</a>',_link('app:admin/+/'.dmString::modulize($call->getType()).'/edit')->params(array('pk'=> $call->getId(),'dm_embed'  => 1))->getHref(),$call->getDescription(), $call->getType())
  );
}
echo $table;
?>

<script type="text/javascript">
   var prepend = '<?php include_partial('callCenter/listHeader');?>';
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
       $('.call_center_list > .dm_widget_inner').load('/index.php/+/main/renderComponent?mod=callCenter&name=list',{},function(){
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

   function initButtonMenus(){
	  $('.bt-flat').each(function(){
	     $(this).menu({
	        content: $(this).next().html(),  //grab content from this page
	        showSpeed: 400,
			callerOnState: "",
			linkHover: "",
			linkHoverSecondary: "ui-state-hover ui-corner-all"
	     });
	  });
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
                                  {"bSortable": true, "sType": "date"},
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
	initButtonMenus();
});
</script>
