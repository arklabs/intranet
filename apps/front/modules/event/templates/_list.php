<?php
use_javascript('lib.dataTable');
use_javascript('geolocation-handler');
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');
use_stylesheet('fg-menu');
use_stylesheet('fb-buttons-menu');
use_stylesheet('tipsy-addons');
use_helper('Date');

// Plugin : List
// Vars : $pluginPager
$sfModule = 'event';

$table = _table('.data_table')->head(
  __('Titulo'),
  __('Cliente'),
  __('Propiedad'),
  __('Estado'),
  __('Fecha'),
  __('Creado Por')
);

foreach ($eventPager as $event)
{
  $address = ($event->getAddress())?$event->getAddress():$event->getProperty()->getAddress();
  $date_start = new sfDate($event->getDateStart());
  $date_end = new sfDate($event->getDateEnd());
  $table->body(
  sprintf('<a  href="%s" class="color-box-trigger" rel="ajax-tipsy" id="'.$event->getId().'" title="Clic para ver los detalles de la cita" > %s </a>', _link('app:admin/+/event/edit')->params(array('pk'=>$event->getId(), 'dm_embed'=>1))->getHref(), $event->getTitle()),
  sprintf('<a  href="%s" class="color-box-trigger" rel="tipsy" title="Clic para ver los detalles de este cliente"> %s </a>', _link('app:admin/+/client/edit')->params(array('pk'=>$event->getClient()->getId(), 'dm_embed'=>1))->getHref(), $event->getClient()),
  sprintf('<a  href="%s" class="color-box-trigger" rel="tipsy" title="Clic para ver los detalles de la propiedad"> %s </a>', _link('app:admin/+/property/edit')->params(array('pk'=>$event->getProperty()->getId(), 'dm_embed'=>1))->getHref(), $address),
  $event->getEventStatus(),
  format_date($date_start->dump(), ($date_start->getHour()!= 0)?'MMM d, y h:m a':'MMM d, y','en'),
  $event->CreatedBy
  );
}
echo $table;
?>
<script type="text/javascript">
   var prepend = '';
   var append = '';

   function loadControls(){ // this js function is called on front.js after datatable loads.
        $('.dataTables_length').prepend(prepend); // put html rendered in partial before "show n entries" control
        $('.dataTables_length').append(append); // put html rendered in partial after "show n entries" control
        $('a[rel=tipsy]').tipsy({fade: true, gravity: 'n'});
        $('a[rel=ajax-tipsy]').tipsy({fade: true, gravity: $.fn.tipsy.autoNS, live: true, html: true,  title: function(){
                tit = $.ajax({
                           type: "GET",
                           url: "/index.php/+/event/getEventBasics/",
                           data: "event-id="+$(this).attr('id'),
                           async: false
                     }).responseText;
                     return tit;
        }});
        
        // other js handling with datatable content here.
        $('.dataTables_paginate.fg-buttonset > .fg-button, .dataTables_paginate.fg-buttonset > span >.fg-button').click(function(){
            $('a[rel=tipsy]').tipsy({fade: true, gravity: 'n'});
            $('a[rel=ajax-tipsy]').tipsy({fade: true, gravity: $.fn.tipsy.autoNS, live: true, html: true,  title: function(){
                tit = $.ajax({
                           type: "GET",
                           url: "/index.php/+/event/getEventBasics/",
                           data: "event-id="+$(this).attr('id'),
                           async: false
                     }).responseText;
                     return tit;
            }});
        });
   };

   function reload(){
       $('#loader').show();
       $('.event_geo_events_list > .dm_widget_inner').load('/index.php/+/main/renderComponent?mod=event&name=geoEventsList',{},function(){
            //initDatatable();
            $('#loader').hide();
	    enableToogableFieldsets();
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
        aaSorting: [ [5, "asc"],[2, "asc"] ],
	aoColumns: [
		      {"bSortable": false},
		      {"bSortable": true, "sClass": "title-col"},
		      null,
		      null,
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
