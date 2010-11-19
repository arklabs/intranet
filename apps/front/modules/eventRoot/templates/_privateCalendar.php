<?php use_stylesheet('tipsy-addons');?>
<?php use_javascript('ui-core');?>
<?php use_javascript('ui-resizable');?>
<?php use_javascript('ui-draggable');?>
<?php use_javascript('fullcalendar.min');?>

<div id="calendar"> </div>
<script type="text/javascript">
    function eventRender(event, element){
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
                                       url: getEventTooltipUrl(),
                                       data: "event-id="+event.id,
                                       async: false
                                 }).responseText;
                                 return tit;
                            }
                        });
                     }
    }
    function getAgendaAvailableViews()
    {
        return 'month,agendaWeek,agendaDay';
    }
    function dayClick(date, allDay, jsEvent, view){
        parent.$.fn.colorbox({href: getDayClickUrl()+date.getUTCFullYear()+'-'+ date.getMonth() +'-'+date.getDate()+' '+date.getHours()+':'+date.getMinutes()+"/allDay/"+allDay+"/dm_embed/1", width:"70%", height:"80%", iframe:true, "css": ["/dmCorePlugin/lib/colorbox/theme3/colorbox.css"],"js":["/dmCorePlugin/lib/colorbox/jquery.colorbox.min.js"]});
    }
    function eventClick(event){
        parent.$.fn.colorbox({href: event.url, width:"80%", height:"80%", iframe:true, "css": ["/dmCorePlugin/lib/colorbox/theme3/colorbox.css"],"js":["/dmCorePlugin/lib/colorbox/jquery.colorbox.min.js"]});
    }
    function getEventTooltipUrl(){
        return "/index.php/+/eventRoot/getEventBasics/";
    }
    function getEventsURL(){
        return "/index.php/+/eventRoot/getMyEvents";
    }
    function getMoveEventUrl(){
        return "/index.php/+/eventRoot/moveEvent";
    }
    function getEventResizeUrl(){
        return "/index.php/+/eventRoot/changeEnd";
    }
    function getDayClickUrl(){
        return "/admin.php/+/eventRoot/new/date/";
    }
</script>
<script type="text/javascript" src="/js/calendar.js">

</script>
