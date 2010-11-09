<?php use_stylesheet('tipsy-addons');?>
<?php use_javascript('ui-core');?>
<?php use_javascript('ui-resizable');?>
<?php use_javascript('ui-draggable');?>
<?php use_javascript('fullcalendar.min');?>

<div id="calendar"> </div>
<script type="text/javascript">
    function getEventsURL(){
        return "/index.php/+/eventPublic/getMyEvents";
    }
    function getMoveEventUrl(){
        return "/index.php/+/event/moveEvent";
    }
    function getEventResizeUrl(){
        return "/index.php/+/event/changeEnd";
    }
    function getDayClickUrl(){
        return "/admin.php/+/event/new/date/";
    }
</script>
<script type="text/javascript" src="/js/calendar.js">

</script>
