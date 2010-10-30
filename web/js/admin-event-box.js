function formatDates(dates){
   dates0 = dates1 = dates;
    result = "";
    if (dates[0]){
        dates0 = dates[0];
    }
    result = result + dates0.getDayName()+', '+dates0.getMonthName() +' '+dates0.getDate()+' '+dates0.getFullYear();
    if (dates[1]){
        dates1 = dates[1];
        result = result + ' &divide; '+dates1.getDayName()+', '+dates1.getMonthName() +' '+dates1.getDate()+' '+dates1.getFullYear();
    }
    return result;
}
function fixHourTo24(completeHour){
    tmp = completeHour.split(' ');
    if (tmp[1].toLowerCase() == 'am'){
        return tmp[0];
    }
    else // its pm
    {
        s = tmp[0].split(':');
        s[0] = s[0]-12+24;
        return s[0]= s[0]+':'+s[1];
    }

}
function fixHourTo12(completeHour){
    tmp = completeHour.split(':');
    if (tmp[0] < 12){
        return tmp[0]+':'+tmp[1]+' AM';
    }
    else {
        return (tmp[0]-12)+':'+tmp[1]+' PM';
    }
}
function SyncDateAndTime(firstTime){
    if (firstTime){
        sHour = $(getRangeDateStartID()).val().split(' ')[1];
        if (sHour == '' || !sHour)
            sHour = '00:00';
        
        if (sHour.split(':')[0]!='00')
            $('#date_start_time').val(fixHourTo12(sHour));
        
        eHour = $(getRangeDateEndID()).val().split(' ')[1];
        if (eHour == '' || !eHour)
            eHour = '00:00:00';

        if (eHour.split(':')[0] != '00')
            $('#date_end_time').val(fixHourTo12(eHour));
        if ($(getRangeDateStartID()).val()!=''){
            $(getRangeDateStartID()).val($(getRangeDateStartID()).val().split(' ')[0]);
            if (getRangeDateStartID()!=getRangeDateEndID())
                $(getRangeDateEndID()).val($(getRangeDateEndID()).val().split(' ')[0]);
        }
    }
    else
    {
        sHour = $('#date_start_time').val();
        if (sHour =='' || !sHour)
            sHour = '00:00 AM';
        eHour = $('#date_end_time').val();
        if (eHour =='' || !eHour)
            eHour = '00:00 AM'
        if ($(getRangeDateStartID()).val()!=''){
            $(getRangeDateStartID()).val($(getRangeDateStartID()).val() + ' '+ fixHourTo24(sHour));
            if (getRangeDateStartID()!=getRangeDateEndID())
                $(getRangeDateEndID()).val($(getRangeDateEndID()).val() + ' '+ fixHourTo24(eHour));
        }
    }
}
function initEventBox(){
    SyncDateAndTime(true);
    $('#calendar-container').css('display', 'block');
    lockDates = getLockDates();
    dates = [new Date(), new Date()];
    if ($(getRangeDateStartID()).val()!= ""){
         dates = $(getRangeDateStartID()).val();
         if ($(getRangeDateEndID()).val()!= "" && getCalendarMode()=='range')
             dates = [$(getRangeDateStartID()).val(), $(getRangeDateEndID()).val()];
         else if (getCalendarMode() == 'range')
             dates = [$(getRangeDateStartID()).val(), $(getRangeDateStartID()).val()];
     }
     $('#calendar-container').DatePicker({
         flat: true,
         format: 'Y-m-d',
         date: dates,
         current: $(getRangeDateStartID()).val().split(' ')[0],
         calendars: getCalendarsNumber(),
         mode: getCalendarMode(),
         starts: 1,
         onChange: function(formated) {
            $('#widgetField span').get(0).innerHTML = formatDates($('#calendar-container').DatePickerGetDate(false));
            formated0 = formated1 = formated;
            if (typeof(formated) != 'string')
                formated0 = formated[0];
            if (typeof(formated) != 'string')
                formated1 = formated[1];
            $(getRangeDateStartID()).val(formated0);
            $(getRangeDateEndID()).val(formated1);
         },
         onRender: function (date){
             if (lockDates == 0)
                disabled_value = false
             else
                 disabled_value = true;

             return {
                disabled: disabled_value
             }
         }
     });
     
     var state = false;
     $('#widgetField a').click(function(){
         if (!state)
             $('#calendar-container').show('slow');
         else
             $('#calendar-container').hide('slow');
         state = !state;
     });
    if ($('.datepicker').get().length == 2){
        $('.datepicker:first').css('display', 'none');
    }

    $('#calendar-container').css('display', 'none');
    if ($(getRangeDateStartID()).val() != ''){
        $('#widgetField span').get(0).innerHTML = formatDates($('#calendar-container').DatePickerGetDate(false));
    }
    /* timepickers */
    $('.hour-trigger').ptTimeSelect({
        zindex: 999,
        onBeforeShow: function(input, widget){
            widget.css('z-index',999);
            if (input.val()!= ""){
                hour = input.val().split(' ');
                jQuery.ptTimeSelect.setHr(hour[1]);
                hour = hour[0].split(':');
                jQuery.ptTimeSelect.setHr(hour[0]);
                jQuery.ptTimeSelect.setMin(hour[1]);
            }
        }
    });

    $("form").submit(function(event){
        SyncDateAndTime(false);
    }); 
 
    return true;
}

jQuery(document).ready(function() {
    $('#date_end_time, #date_start_time').css('width', '70px');
    initEventBox();
});

