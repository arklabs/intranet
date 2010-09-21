<?php
use_javascript('lib.dataTable');
use_stylesheet('dates-row-colors');
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');

use_helper('Date');

// Plugin : List
// Vars : $pluginPager
$sfModule = 'event';

$table = _table('.data_table')->head(
  __('Titulo'),
  __('Cliente'),
  __('Fecha Inicio'),
  __('Creado Por'),
  __('Estado')
);

foreach ($eventPager as $event)
{
  $assignedTo = $event->getDmUser(); 
  $date_start = new sfDate($event->getDateStart());
  $date_end = new sfDate($event->getDateEnd());
  $table->body(
  sprintf('<a href="%s" rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $event->getId(),'dm_embed'  => 1))->getHref(), $event->getDescription(), $event->getTitle()),
  sprintf('<a href="%s"  rel="tipsy" original-title="%s" class="color-box-trigger"> %s</a>',_link('app:admin/+/client/edit')->params(array('pk'=> $event->getClient()->getId(),'dm_embed'  => 1))->getHref(), $event->getClient()->getHouse().' - Clic para ver los detalles del cliente', $event->getClient()->__toString()),
  format_date($date_start->dump(), ($date_start->getHour()!= 0)?'MMM d, y h:m a':'MMM d, y','en'),
  $event->CreatedBy,
  get_partial('event/agentAssignDateStatusCell', array('event'=>$event, 'agentList'=>$agentList))
  );
}
echo _open('form', array('method'=>'POST', 'action'=>_link('app:front/+/event/batchChangeStatus')->getHref(), 'name'=>'eventListForm'));
echo $table;
echo _close('form');
?>
<script type="text/javascript">
   var prepend = '';
   var append = '';
   
   function loadControls(){ // this js function is called on front.js after datatable loads.
        $('.dataTables_length').prepend(prepend); // put html rendered in partial before "show n entries" control
        $('.dataTables_length').append(append); // put html rendered in partial after "show n entries" control
        $('a[rel=tipsy]').tipsy({fade: true, gravity: 'n'});
        $('.color-box-trigger').click(function(){
            parent.$.fn.colorbox({href: $(this).attr('href'), width:"70%", height:"80%", iframe:true, "css": ["/dmCorePlugin/lib/colorbox/theme3/colorbox.css"],"js":["/dmCorePlugin/lib/colorbox/jquery.colorbox.min.js"]});
            return false;
          });
        $('select.ev-asign-agent-list').each(function(){
            $(this).change(function(){
         	   agentChangeTriggerHandler($(this).attr('id'), this);    
            });
        });
        $('.reag-time').ptTimeSelect({
     		onBeforeShow: function(input, widget){
    				$('#ptTimeSelectCntr').css('left',  $('body').innerWidth()- widget.innerWidth()- 22);
     	   		if (input.val()!= ""){
     	   			hour = input.val().split(' ');
     		   		hour = hour[0].split(':');
     		   		jQuery.ptTimeSelect.setHr(hour[0]);
     		   		jQuery.ptTimeSelect.setMin(hour[1]);
     	   		}  
   		 	},
   		 	onClose: function(input){
   		 		hour = input.val().split(' ');
   		 		if (hour.length > 1){
   	  		 		pm = hour[1];
   		 			hour = hour[0].split(':');
   		 			if (pm == 'PM')
   	  		 			hour[0] = hour[0] - 1 + 13;
   	  		 		input.val(hour[0]+':'+hour[1]+':00');
   		 		}
   		 		else{
   		 			hour = hour[0].split(':');
   		 			input.val(hour[0]+':'+hour[1]+':00');
   		 		}
   		 		
   	  		}
 		 }
         ); 
        var currentSingleCalendar = null; 
        $('a.reag-date').each(function(){
 			$(this).DatePicker({
 					format: 'Y-m-d',
 					date: $(this).html(),
 					current: $(this).html(),
 					starts: 1,
 					position: 'r',
 					onBeforeShow: function(){
 			   			$(this).DatePickerSetDate($(this).html(), true);
 			   			currentSingleCalendar = $(this);
 			       	},
 			       	onChange: function(formated, dates){
 			       		currentSingleCalendar.html(formated+" ");
 			           	currentSingleCalendar.DatePickerHide();
 			           	
 			        },
 			        onRender: function(date) {
 				        now = new Date();
 			    		return {
 			    			disabled: (date.valueOf() < now.valueOf())
 			    		}
 			    	}
 			  });
            });
        $('.apply-new-date').click(function(){
	       	$('.small-loader[id="'+$(this).attr('id')+'"]').show();
	       	$.ajax({
	           	url: '/+/event/changeDate',
	            data: 'new-date='+$(this).parent().find('.reag-date').html()+' '+$(this).parent().find('.reag-time').val()+'&event='+$(this).attr('id'),
	            success: function(){
	            	id = this.data;
	            	id = id.split('=');
	            	id = id[2];
	            	$('.small-loader[id="'+id+'"]').hide();
	            	refreshStatus(id);
	            },
	            complete: function(){
	            },
	            error: function(){
	            	id = this.data;
	            	id = id.split('=');
	            	id = id[2];
	            	$('.small-loader[id="'+id+'"]').hide();
	            	alert('Ha ocurrido un problema reagendando la cita.');
	            }	 
	         });
        });
   };
   
   function reload(){
       $('#loader').show();
       $('.list-container').load('/+/main/renderComponent?mod=event&name=agentAssignDates&date_start='+getRangeDateStart()+'&date_end='+getRangeDateEnd(),{},function(){
            //initDatatable();
            $('#loader').hide();
            loadNotifications();
       });
       //location.reload(); // to reload the whole page
   }
   
   function initDatatable(){
    if($dataTable = $('table.data_table').orNot())
    {
      $dataTable.dataTable({
        bJQueryUI: true,
        bPaginate: false,
        sPaginationType: "full_numbers",
        aaSorting: [ [4, "asc"],[2, "asc"] ],
		aoColumns: [
		      {"bSortable": false},
		      {"bSortable": true, "sClass": "title-col"},
		      null,
		      {"bSortable": true, "sType": "date"},
		      {"bSortable": true},
		   ]
      });
       $('th.ui-state-default:first').css('min-width', '1.5em');
       $('th.ui-state-default:first').css('width', '1.5em');
       $('.data_table').css('width', '100%');
       // building status list
       loadControls();
      }
      
	
  }
  function agentChangeTriggerHandler(eventId, thisObject){
	  $('img.ev-status[id="'+eventId+'"]').show();
		$.ajax({
				type: 'POST',
				url: '/+/event/assignAgent',
				data: 'ag-id='+$(thisObject).find('option:selected').val()+'&ev-id='+$(thisObject).attr('id'),
				success: function(){
					evId = this.data.split('=');
					$('img.ev-status[id="'+evId[2]+'"]').parent().html('<img src="/theme/images/loader-small.gif" style="display: none; float: right;" class="ev-status small-loader" id="'+evId[2]+'">');
					refreshStatus(evId[2]);
				},
				complete: function(){
				},	
				error: function(){
					$('img.ev-status[id="'+id+'"]').parent().html('<img src="/theme/images/loader-small.gif" style="display: none; float: right;" class="ev-status small-loader" id="'+id+'">');
					alert('Error mientras se asignaba esta cita.');
				}
			});
  }
  function refreshStatus(eventId){
	  $('img.ev-status[id="'+eventId+'"]').show();
	  $.ajax({
			type: 'GET',
			url: '/+/event/refreshStatus',
			data: 'ev-id='+eventId,
			success: function(response){
				id = this.data.substr(6);
				img = $('img.ev-status[id="'+id+'"]');
				img.hide();
				img.parent().html(response);
			},
			complete: function(response){
				id = this.data.substr(6);
				$('select.ev-asign-agent-list[id="'+id+'"]').change(function(){
					agentChangeTriggerHandler(id);
				});
			},
			error: function(){
				id = this.data.substr(6);
				img = $('img.ev-status[id="'+id+'"]');
				img.hide();
				alert('Error mientras se obtenia el estado de esta cita');
			}
         });
  }
  $(document).ready(function(){
	initDatatable();
  });



</script>
