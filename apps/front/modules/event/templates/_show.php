<style type="text/css">
    td{ padding-right: 15px;}
</style>
<?php // Vars: $event
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('fb-buttons-menu');
use_stylesheet('print', 'first' ,array('media'=>'print'));

echo $event->buildEventInformationBasics();

echo _tag('br');
echo _tag('br');
echo _tag('br');
echo _open('a.bt-flat.fg-button.fg-button-icon-left.ui-widget.ui-state-default.ui-corner-all', array('tabindex'=>0, 'href'=>'#', 'onclick'=>'window.print()', 'style'=>'margin-left: 0px;' )).
     _tag('span.ui-icon.ui-icon-print',"")."Imprimir"._close('a');