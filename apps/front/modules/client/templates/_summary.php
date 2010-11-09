<?php
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('fb-buttons-menu');
use_stylesheet('print', 'first' ,array('media'=>'print'));

echo _tag('h2','Resumen del cliente: '.$client);
foreach ($clientSummary as $summary){
    echo _tag('div '.((array_key_exists('attributes', $summary))?$summary['attributes']:' '), get_partial(sprintf('%s/%s', $summary['partialModuleName'], $summary['partialName']), $summary['data']));
}

echo _open('a.bt-flat.fg-button.fg-button-icon-left.ui-widget.ui-state-default.ui-corner-all', array('tabindex'=>0, 'href'=>'#', 'onclick'=>'window.print()', 'style'=>'margin-left: 10px;' )).
     _tag('span.ui-icon.ui-icon-print',"")."Imprimir"._close('a');
echo _tag('br');
echo _tag('br');
echo _tag('label',' ');
