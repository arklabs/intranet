<?php
use_stylesheet('ark-icons-2-16');
#vars
### client
$sfModule = 'clientFile';
if (count($client->getClientFile()) == 0){
    echo _tag('label.ui-helper-reset style="padding-left: 10px;"', "No tiene tramites").'<br/>';
}
else {
    echo _open('a.bt-flat.fg-button.fg-button-icon-right.ui-widget.ui-state-default.ui-corner-all', array('tabindex'=>0, 'href'=>'#'));
    echo _tag('span.ui-icon.ui-icon-carat-1-s',"");
    echo "Trámites";
    echo _close('a');
    echo _open('div.hidden');
    echo _open('ul');
        foreach ($client->getClientFile() as $file){
            if ($file->getFileType() == "Modification")
                $iconClass = "ark-icon-file-modification";
            elseif ($file->getFileType() == "Short Sale")
                $iconClass = "ark-icon-file-ssale";
            elseif ($file->getFileType() == "Real Estate" || $file->getFileType() == "Real State" )
                $iconClass = "ark-icon-file-realestate";
            else
                $iconClass = "";
            $date = new sfDate($file->getDateStart());
            $aTagContent = sprintf('<a href="%s"  class="color-box-trigger ark-clear-left"><span class="ark-icon-2-16 %s icon-fl"></span> %s  %s</a>',_link('app:admin/+/'.$sfModule.'/edit')->params(array('pk'=> $file->getId(),'dm_embed'  => 1))->getHref(),$iconClass, $file->getFileType(), $date->o2h());
            echo _tag('li', $aTagContent);
        }
    echo _close('ul');
    echo _close('div');
}
?>


