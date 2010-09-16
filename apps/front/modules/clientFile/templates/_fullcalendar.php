<?php use_javascript('ui-core');?>
<?php use_javascript('ui-resizable');?>
<?php use_javascript('ui-draggable');?>
<?php use_javascript('fullcalendar.min');?>
<div id="calendar"> </div>
<script type="text/javascript">
    function getEventsURL(){
        return "/index.php/+/clientFile/getMyEvents";
    }
    function getMoveEventUrl(){
        return "/index.php/+/clientFile/moveEvent";
    }
    function getEventResizeUrl(){
        return "/index.php/+/clientFile/changeEnd";
    }
    function getDayClickUrl(){
        return "/admin.php/+/clientFile/new/date/";
    }
</script>
<script type="text/javascript" src="/js/calendar.js">

</script>