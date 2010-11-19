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
                        right: getAgendaAvailableViews()
                },
                editable: true,
                events: getEventsURL(),
                eventClick: function(event) {
                    eventClick(event);
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
                   dayClick(date, allDay, jsEvent, view);
                },
                eventRender: function(event, element) {
                     eventRender(event, element);
                },
                loading: function(isLoading){
                    if (isLoading)
                        $('#loader').show();
                    else{
                        $('#loader').hide();
                    }
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