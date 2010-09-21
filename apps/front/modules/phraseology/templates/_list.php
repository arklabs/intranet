<?php // Vars: $phraseologyPager
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');
use_javascript('lib.dataTable');
use_stylesheet('ark-icons-1-16');

$sfModule = 'client';

$table = _table('.data_table')->head(
  __('Fraseologia'),
  __('Votacion'),
  __('Citas exitosas')
);

foreach ($phraseologyPager as $phraseology)
{
  $table->body(
 	 get_partial('phraseology/contentCell', array('phraseology'=>$phraseology)),
 	 _open('a.pos-votation ', array('style'=>"float: left;", 'href'=>"#", 'ph-id'=>$phraseology->getId()))
	 	 ._tag('span.ark-icon-1-16.ark-icon-vote-plus')
	 	 ._tag('label',$phraseology->getPosRank())
 	 ._close('a')
 	 ._open('a.neg-votation', array('style'=>"float: left; padding-left: 10px;",'href'=>"#", 'ph-id'=>$phraseology->getId()))
 	 	._tag('span.ark-icon-1-16.ark-icon-vote-less')
 	 	._tag('label',$phraseology->getNegRank())
 	 ._close('a'),
 	 $phraseology->getSuccessfullDatesCount()
  );
}
//echo _open('form', array('method'=>'POST', 'action'=>'/+/event/batchChangeStatus', 'name'=>'eventListForm'));
echo $table;
//echo _close('form');
?>
<script type="text/javascript">
   var prepend = '<?php echo ($sf_user->hasPermission('phraseology_adicionar_entradas') || $sf_user->isSuperAdmin())?get_partial('phraseology/newButton'):'';?>';
   var append = '';
   function enableVotation(){
	   $('a.pos-votation').click(function(){
           $.ajax({
               type: 'POST',
               url: '/+/phraseology/vote',
               data: 'value=1&ph-id='+ $(this).attr('ph-id'),
               success: function(){
               			id = this.data.split('=');
               			id = id[2];
           				$('a.pos-votation[ph-id="'+id+'"] label').html($('a.pos-votation[ph-id="'+id+'"] label').html()-1+ 2);    			
               		},
               error: function(){ alert('Ha ocurrido un error realizando la votacion');}
           });
       });
       $('a.neg-votation').click(function(){
           $.ajax({
               type: 'POST',
               url: '/+/phraseology/vote',
               data: 'value=-1&ph-id='+ $(this).attr('ph-id'),
               success: function(){
               			id = this.data.split('=');
               			id = id[2];
           				$('a.neg-votation[ph-id="'+id+'"] label').html($('a.neg-votation[ph-id="'+id+'"] label').html()-1+2);    			
               		},
               error: function(){ alert('Ha ocurrido un error realizando la votacion');}
           });
       });
   }
   function loadControls(){ // this js function is called on front.js after datatable loads.
        $('.dataTables_length').prepend(prepend); // put html rendered in partial before "show n entries" control
        $('.dataTables_length').append(append); // put html rendered in partial after "show n entries" control
        $('a[rel=tipsy]').tipsy({fade: true, gravity: 'n'});

        $('.dataTables_paginate.fg-buttonset > .fg-button, .dataTables_paginate.fg-buttonset > span >.fg-button').click(function(){
            $('a[rel=tipsy]').tipsy({fade: true, gravity: 'n'});
        });
        enableVotation();
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
	        aaSorting: [ [2, "desc"], [1, "desc"], [0,"asc"] ],
		 	aoColumns: [
					      {"bSortable": true },
					      {"bSortable": true },
					      {"bSortable": true},
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
