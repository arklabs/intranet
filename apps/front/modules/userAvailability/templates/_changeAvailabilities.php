<?php
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('fg-menu');
use_stylesheet('fb-buttons-menu');
use_javascript('enable-tooglable-fieldset');

echo _tag('h2.#dboard-toogle-trigger.fieldset_title.ui-helper-reset.collapsable-box-header style="text-align: left;width: 300px !important;"', '<span class="ui-icon ui-icon-triangle-1-e fieldset_icon"></span><span class="fieldset_name">Cambiar Disponiblidades</span>');
echo _open('div#ibutton-area-to-hide', array('style'=>''));

echo _tag('span#month-list style="float: left;"', $monthsWidget->render('av-month-selector'));
echo _tag('span#day-list style="float: left;"', $daysWidget->render('av-day-selector'));
echo _tag('span style="float: left; margin-right: 5px;margin-left: 5px;"', $iButtonWidget->render('av-selector'));
echo _tag('span style="float: left;"',
        _open('a.#process-av-trigger.bt-flat.fg-button.fg-button-icon-right.ui-widget.ui-state-default.ui-corner-all', array('tabindex'=>0, 'href'=>'#')).
        _tag('span.ui-icon.ui-icon-gear',"")."Procesar"._close('a')
        );
echo _close('div');

echo _tag('div.clear');


?>
<script type="text/javascript">
    $('#process-av-trigger').click(function(){
        $('#loader').show();
        var months = {}; var days = {};
        $('#month-list > .checkbox_list > li > input').each(function(i, val){
            if ($(val).attr('checked'))
                months[$(val).val()] = 1;
        });
        $('#day-list > .checkbox_list > li > input').each(function(i, val){
            if ($(val).attr('checked'))
                days[$(val).val()] = 1;
        });
        $.ajax({
            type: "POST",
            url: "/index.php/+/userAvailability/changeAvailability",
            data: {"meses": months,'dias': days, 'value': $('input#av-selector').attr('checked') },
            success: function(){
                $('#loader').hide();
                reload();
            },
            error: function(){
                $('#small-loader').hide();
                alert('Un error inesperado ha evitado que se complete la operación.');
            }
        });
    });
</script>