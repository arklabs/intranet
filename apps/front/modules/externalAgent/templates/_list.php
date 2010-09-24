
<?php
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');
use_stylesheet('fg-menu');
use_stylesheet('fb-buttons-menu');
use_javascript('lib.dataTable');
use_javascript('fg-menu');
use_javascript('fg-menu-handling');
$sfModule = 'externalAgent';

$table = _table('.data_table')->head(
  //_tag('input', array('type'=>'checkbox', 'class'=>'check-all')),
  __('Nombre'),
  __('TelÃ©fono'),
  __('Compa&ntilde;&iacute;a'),
  __('Acciones')
);

foreach ($externalAgentPager as $externalAgent)
{
  $table->body(
  sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $externalAgent->getId(),'dm_embed'  => 1))->getHref(), $externalAgent->getUsername(), $externalAgent->getFirstName().' '.$externalAgent->getLastName()),
  ($externalAgent->getPhone())?sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $externalAgent->getId(),'dm_embed'  => 1))->getHref(), 'Clic para modificar el telÃ©fono de este afiliado', $externalAgent->getPhone()):sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $externalAgent->getId(),'dm_embed'  => 1))->getHref(), 'Clic para agregar el telÃ©fono de este afiliado', "No asignado"),
  ($externalAgent->getExternalAgentCompany())?sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $externalAgent->getId(),'dm_embed'  => 1))->getHref(), 'Clic para modificar cambiar la compañía de este afiliado', $externalAgent->getPhone()):sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $externalAgent->getId(),'dm_embed'  => 1))->getHref(), 'Clic para asignar la compañía de este afiliado', "No asignado"),
  _open('a.bt-flat.fg-button.fg-button-icon-right.ui-widget.ui-state-default.ui-corner-all', array('tabindex'=>0, 'href'=>'#')).
  _tag('span.ui-icon.ui-icon-carat-1-s',"")."Acciones"._close('a').
  _open('div.hidden').
  get_partial('externalAgent/externalAgentActionsPanel', array('externalAgent'=>$externalAgent))._close('div')
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
       $('.externalAgent_list > .dm_widget_inner').load('/index.php/+/main/renderComponent?mod=externalAgent&name=list',{},function(){
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
					      {"bSortable": true},
					      {"bSortable": true, "sClass": "title-col"},
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