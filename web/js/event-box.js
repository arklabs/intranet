function formatDates(dates){
    return dates[0].getDayName()+', '+dates[0].getMonthName() +' '+dates[0].getDate()+' '+dates[0].getFullYear()+' &divide; '+dates[1].getDayName()+', '+dates[1].getMonthName() +' '+dates[1].getDate()+' '+dates[1].getFullYear();
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
        sHour = $('#event_form_date_start').val().split(' ')[1];
        if (sHour == '')
            sHour = '00:00';
        
        if (sHour.split(':')[0]!='00')
            $('#date_start_time').val(fixHourTo12(sHour));
        
        eHour = $('#event_form_date_end').val().split(' ')[1];
        if (eHour == '')
            eHour = '00:00:00';

        if (eHour.split(':')[0] != '00')
            $('#date_end_time').val(fixHourTo12(eHour));

        $('#event_form_date_start').val($('#event_form_date_start').val().split(' ')[0]);
        $('#event_form_date_end').val($('#event_form_date_end').val().split(' ')[0]);
    }
    else
    {
        sHour = $('#date_start_time').val();
        if (sHour =='')
            sHour = '00:00 AM';
        eHour = $('#date_end_time').val();
        if (eHour =='')
            eHour = '00:00 AM'
         
        $('#event_form_date_start').val($('#event_form_date_start').val() + ' '+ fixHourTo24(sHour));
        $('#event_form_date_end').val($('#event_form_date_end').val() + ' '+ fixHourTo24(eHour));
    }
}
function initEventBox(){
    SyncDateAndTime(true);
    $('#calendar-container').css('display', 'block');
    lockDates = getLockDates();
    dates = [new Date(), new Date()];
    if ($('#event_form_date_start').val()!= ""){
         dates = [$('#event_form_date_start').val()];
         if ($('#event_form_date_end').val()!= "")
             dates = [$('#event_form_date_start').val(), $('#event_form_date_end').val()];
         else
             dates = [$('#event_form_date_start').val(), $('#event_form_date_start').val()];
     }
     $('#calendar-container').DatePicker({
         flat: true,
         format: 'Y-m-d H:M',
         date: dates,
         current: $('#event_form_date_start').val().split(' ')[0],
         calendars: 2,
         mode: 'range',
         starts: 1,
         onChange: function(formated) {
            $('#widgetField span').get(0).innerHTML = formatDates($('#calendar-container').DatePickerGetDate(false));
            $('#event_form_date_start').val(formated[0]);
            $('#event_form_date_end').val(formated[1]);
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

     $('#widgetField span').get(0).innerHTML = formatDates($('#calendar-container').DatePickerGetDate(false));
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
    
    $(".button").click(function() {
        //$(".button").css('display','none');
        SyncDateAndTime(false);
        var jsonForm = {"form": {
                'title': $('#event_form_title').val(),
                'category_id': $('#event_form_category_id').val(),
                'status_id': $('#event_form_status_id').val(),
                'date_start': $('#event_form_date_start').val(),
                'date_end': $('#event_form_date_end').val(),
                'description': $('#event_form_description').val(),
                '_csrf_token': $('#event_form__csrf_token').val(),
                'id': $('#event_form_id').val()
        }};
        $('#local-loader').show();
        $('.content').load($('#facebox form').attr('action'),{data: jsonForm, clicked_button: $(this).val()}, function(data){
            if (data == '') {
                $('#local-loader').hide();
                $.facebox.close();
            }
            else
                initEventBox();
        });
    });
    /*$("form").submit(function(event){
        event.preventDefault();
    }); */
 
    return true;
}
jQuery(document).ready(function() {
    initEventBox();
});

