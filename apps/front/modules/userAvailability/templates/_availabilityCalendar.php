<?php use_stylesheet('ibutton.min');?>

<?php //use_javascript('ui-core'); /**NO SE NECESITAN EN ESTE CALENDARIO**/?>
<?php //use_javascript('ui-resizable');?>
<?php //use_javascript('ui-draggable');?>

<?php use_javascript('fullcalendar.min');?>
<style type="text/css">
    .day-on{ background-color: rgba(0,255,0,0.2) !important;}
    .day-off{ background-color: rgba(255,0,0,0.2) !important;}
</style>

<div id="calendar"> </div>

<script type="text/javascript" src="/js/jquery.ibutton.min.js"></script>

<script type="text/javascript">
    function makeButtonWidget(eventid){
        $("input#"+eventid).iButton({
                 labelOn: "YES"
               , labelOff: "NO"
               , change: function ($input){
                     if ($input.attr('checked')){
                         $('.fc-day'+$input.attr('day')).removeClass('day-off');
                         $('.fc-day'+$input.attr('day')).addClass('day-on');
                     }
                     else
                     {
                         $('.fc-day'+$input.attr('day')).removeClass('day-on');
                         $('.fc-day'+$input.attr('day')).addClass('day-off');
                     }
                     $.ajax({
                         type: "GET",
                         data: "id="+$input.attr('id')+'&value='+$input.attr('checked'),
                         url: '/index.php/+/userAvailability/toogle',
                         success: function(){

                         },
                         error: function(){
                             alert("Ha ocurrido un error inesperado y esta operación no ha surtido efecto.");
                             $input.iButton("destroy");
                             if ($input.attr('checked')){
                                 $('.fc-day'+$input.attr('day')).removeClass('day-on');
                                 $('.fc-day'+$input.attr('day')).addClass('day-off');
                                 $input.removeAttr('checked')
                                 makeButtonWidget(eventid);
                             }
                             else
                             {
                                 $('.fc-day'+$input.attr('day')).removeClass('day-off');
                                 $('.fc-day'+$input.attr('day')).addClass('day-on');
                                 $input.attr('checked', 'checked');
                                 makeButtonWidget(eventid);
                             }
                             
                         }

                     });
                 }
            }
            );
    }
    function eventRender(event, element){
        //alert(element.html());
            checked = '';
            if (event.type == 'a') {
                checked = 'checked="checked"';
                $('.fc-day'+event.day).removeClass('day-off');
                $('.fc-day'+event.day).addClass('day-on');
            }
            else{
                $('.fc-day'+event.day).removeClass('day-on');
                $('.fc-day'+event.day).addClass('day-off');
            }

            element.find('a').replaceWith('<input type="checkbox" day="'+event.day+'" id="'+event.id+'"'+checked+' >');
            makeButtonWidget(event.id);
            if (!event.enabled)
                $("input#"+event.id).iButton('disable');
        /*view = $('#calendar').fullCalendar('getView');
                     tmp = element.find('a').html('<span><div class="ev-ct-holder">'+'</div>'+element.find('a').html());
                     if (view.name != 'month' &&  !event.allDay){
                        element.find('a>span:last').after($('<br/><span class="fc-ev-description">'+event.description+'</span>'));
                     }
                     if ((view.name == 'month' || event.allDay) && event.description!='') {
                         element.attr("original-title", event.description);
                         element.tipsy({fade: true, gravity: $.fn.tipsy.autoNS, live: true,  html: true, title: function(){
                                 tit = $.ajax({
                                       type: "GET",
                                       url: getEventTooltipUrl(),
                                       data: "event-id="+event.id,
                                       async: false
                                 }).responseText;
                                 return tit;
                            }
                        });
                     } */
    }
    function getAgendaAvailableViews()
    {
        return '';
    }
    function dayClick(date, allDay, jsEvent, view){
        
        return false;
    }
    function eventClick(event){
    }
    function getEventTooltipUrl(){
        return "/index.php/+/eventPublic/getEventBasics/";
    }
    function getEventsURL(){
        return "/index.php/+/userAvailability/getAvailability";
    }
    function getMoveEventUrl(){
        return "/index.php/+/eventPublic/moveEvent";
    }
    function getEventResizeUrl(){
        return "/index.php/+/eventPublic/changeEnd";
    }
    function getDayClickUrl(){
        return "/admin.php/+/eventPublic/new/date/";
    }
</script>
<script type="text/javascript" src="/js/calendar.js">

</script>
