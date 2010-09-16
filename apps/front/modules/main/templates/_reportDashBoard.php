<?php
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('reset');
use_stylesheet('dashboard');
use_javascript('check-reports-menu-item');
use_javascript('enable-tooglable-fieldset');
echo _tag('h2.#dboard-toogle-trigger.fieldset_title.ui-helper-reset.collapsable-box-header style="text-align: left;width: 300px !important;"', '<span class="ui-icon ui-icon-triangle-1-e fieldset_icon"></span><span class="fieldset_name">Reportes</span>');
echo _open('div.fieldset_content.ui-helper-reset.ui-accordion-content-active.ui-helper-hidden.collapsable-box');
echo _open('ul.shortcut-buttons-set');
     foreach (array_keys($dashDescription) as $itemKey){
        $item = $dashDescription[$itemKey]; 
        if (is_null($item['credentials']) || $sf_user->hasCredential(array_keys($item['credentials']))){
        echo _open('li');?>
            <a class="shortcut-button <?php foreach (array_keys($item['extra-classes']) as $extra) echo $extra.' ';?>" href="<?php echo _link($item['url'])->params((count($item['params']))?$item['params']:array())->getHref();?>">
            <?php 
                $extraClasses = "";
                foreach (array_keys($item['holder-classes']) as $extra) $extraClasses.= '.'.$extra;
                echo _tag('span', _tag('span.sprite-holder'.$extraClasses).$item['title']);
            ?>
            </a>
        
        <?php
        }
    }
    echo _close('ul');
echo _close('div');
echo _open('div.clear', array('style', 'width:100%'));
echo _close('div');
?>