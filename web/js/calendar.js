function fullcalendarinit() {
    //  calendar inself
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        $('#calendar').fullCalendar({
                header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                },
                editable: true,
                events: getEventsURL(),
                eventClick: function(event) {
                    parent.$.fn.colorbox({href: event.url, width:"80%", height:"80%", iframe:true, "css": ["/dmCorePlugin/lib/colorbox/theme3/colorbox.css"],"js":["/dmCorePlugin/lib/colorbox/jquery.colorbox.min.js"]});
                    return false;
                },
                eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
                    $('.tipsy').replaceWith('');
                    if (!confirm("Está seguro que quiere cambiar el evento?")) {
                            revertFunc();
                    }
                    else {
                        $.post(getMoveEventUrl(),{id: event.id, dayDelta: dayDelta, minuteDelta:  minuteDelta, allDay: allDay},function(){
                            loadNotifications();
                        });
                    }

                },
               eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
                   $('.tipsy').replaceWith('');
                    if (!confirm("Está operación cambiará la fecha de culminación de su evento. Está seguro que desea continuar?")) {
                        revertFunc();
                    }
                    else {
                        $.post(getEventResizeUrl(),{id: event.id, dayDelta: dayDelta, minuteDelta:  minuteDelta},function(){
                            loadNotifications();
                        });
                    }
               },
               dayClick: function(date, allDay, jsEvent, view) {
                   parent.$.fn.colorbox({href: getDayClickUrl()+date.getUTCFullYear()+'-'+ date.getMonth() +'-'+date.getDate()+' '+date.getHours()+':'+date.getMinutes()+"/allDay/"+allDay+"/dm_embed/1", width:"70%", height:"80%", iframe:true, "css": ["/dmCorePlugin/lib/colorbox/theme3/colorbox.css"],"js":["/dmCorePlugin/lib/colorbox/jquery.colorbox.min.js"]});
                },
                eventRender: function(event, element) {
                     view = $('#calendar').fullCalendar('getView');
                     tmp = element.find('a').html('<span><div class="ev-ct-holder">'+'</div>'+element.find('a').html());
                     if (view.name != 'month' &&  !event.allDay){
                        element.find('a>span:last').after($('<br/><span class="fc-ev-description">'+event.description+'</span>'));
                     }
                     if ((view.name == 'month' || event.allDay) && event.description!='') {
                         element.attr("original-title", event.description);
                         element.tipsy({fade: true, gravity: $.fn.tipsy.autoNS, live: true,  html: true, title: function(){
                                 tit = $.ajax({
                                       type: "GET",
                                       url: "/index.php/+/event/getEventBasics/",
                                       data: "event-id="+event.id,
                                       async: false
                                 }).responseText;
                                 return tit;
                            }
                        });
                     }
                },
                loading: function(isLoading){
                    if (isLoading)
                        $('#loader').show();
                    else
                        $('#loader').hide();
                }
          });
          // after calendar initialization capture colorbox on close
          $(document).bind('cbox_closed', function(){
              reload();
          });
          return true;
}
function reload(){
    loadNotifications();
    $('#calendar').fullCalendar( 'refetchEvents' );
    $('#calendar').fullCalendar('render');
}
$(document).ready(function(){
       if ($('#calendar')!=null) // si se ha cargado el calendario inicializalo.
           fullcalendarinit();
});