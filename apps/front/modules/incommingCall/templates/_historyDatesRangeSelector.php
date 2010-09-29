<?php
use_stylesheet('/arkCompleteJQueryDateTimePickerPlugin/css/datepicker.css');
use_stylesheet('/arkCompleteJQueryDateTimePickerPlugin/css/date-picker-widget.css');
use_javascript('/arkCompleteJQueryDateTimePickerPlugin/js/datepicker.js');
use_javascript('lib.data');
use_stylesheet('dataTable');
use_javascript('lib.dataTable');

use_stylesheet('fg-menu');
use_stylesheet('fb-buttons-menu');
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');

echo _open('div',array('style'=>'width: 460px;'));
echo _open('a.fg-button.fg-button-icon-right.ui-widget.ui-state-default.ui-corner-all', array('id'=>"load-dates-trigger", 'tabindex'=>0, 'href'=>'#','style'=>'float:right;'));
echo    "&nbsp&nbsp;Listar Llamadas";
echo _close('a');
echo _open('div#widgetField',array('style'=>''));
echo _tag('span');
echo _open("a", array('href'=>"#"))."Select date range"._close('a');
echo _close('div');
echo _tag('div#calendar-container');
echo _close('div');
echo _tag('div.min-border-top.list-container', '');
?>
<script type="text/javascript">
    function formatDates(dates){
        return dates[0].getDayName()+', '+dates[0].getMonthName() +' '+dates[0].getDate()+' '+dates[0].getFullYear()+' &divide; '+dates[1].getDayName()+', '+dates[1].getMonthName() +' '+dates[1].getDate()+' '+dates[1].getFullYear();
    }

    function getRangeDateStart(){
        dates = $('#calendar-container').DatePickerGetDate(true);
        return dates[0];
    }

    function getRangeDateEnd(){
        dates = $('#calendar-container').DatePickerGetDate(true);
        return dates[1];
    }
    $(document).ready(function(){
         var now3 = new Date();
         var now4 = new Date()
        $('#calendar-container').DatePicker({
             flat: true,
             format: 'Y-m-d',
             date: ["<?php echo $dateStart;?>", "<?php echo $dateEnd;?>"],// obtain
             //current: $(getRangeDateStartID()).val().split(' ')[0],
             calendars: 3,
             mode: 'range',
             starts: 1,
             onChange: function(formated) {
                $('#widgetField span').get(0).innerHTML = formatDates($('#calendar-container').DatePickerGetDate(false));
             }
         });
         $('#calendar-container').toggle();
         $('#widgetField a').click(function(){
             $('#calendar-container').toggle("fast");
         });
         $('#widgetField span').get(0).innerHTML = formatDates($('#calendar-container').DatePickerGetDate(false));
         $('#load-dates-trigger').click(function(){
             $('#loader').show();
             $.ajax({
                 	 url: '/index.php/+/main/renderComponent?mod=incommingCall&name=list&date_start='+getRangeDateStart()+'&date_end='+getRangeDateEnd(),
                 	 success: function(response){
            	     	$('.list-container').html(response);
            	     	$('#loader').hide();
                     },
                     error: function(){
                         alert('Ha ocurrido un error mientras se obtenía la lista.');
                     }
                 });
         });
         $('#load-dates-trigger').click();
    });
</script>
