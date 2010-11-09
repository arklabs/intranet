<?php
use_stylesheet('ark-icons-2-16');
#vars
### client
$sfModule = 'event';
if (count($client->getEvent()) == 0){
    echo _tag('label.ui-helper-reset style="padding-left: 10px;"', "No tiene Citas").'<br/>';
}
else {
    echo _open('a.bt-flat.fg-button.fg-button-icon-right.ui-widget.ui-state-default.ui-corner-all', array('tabindex'=>0, 'href'=>'#'));
    echo _tag('span.ui-icon.ui-icon-carat-1-s',"");
    echo "Citas";
    echo _close('a');
    echo _open('div.hidden');
    echo _open('ul');
        foreach ($client->getEvent() as $file){
            if ($file->getFileType() == "Modification")
                $iconClass = "ark-icon-file-modification";
            elseif ($file->getFileType() == "Short Sale")
                $iconClass = "ark-icon-file-ssale";
            elseif ($file->getFileType() == "Real Estate" || $file->getFileType() == "Real State" )
                $iconClass = "ark-icon-file-realestate";
            else
                $iconClass = "";
            $date = new sfDate($file->getDateStart());
            $aTagContent = sprintf('<a href="%s"  class="ark-clear-left"><span class="ark-icon-2-16 %s icon-fl"></span> %s</a>',_link($file)->getHref(),$iconClass, $file);
            echo _tag('li', $aTagContent);
        }
    echo _close('ul');
    echo _close('div');
}
?>


