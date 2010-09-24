<?php
// Variables 
// $dashDescription

use_stylesheet('reset');
use_stylesheet('dashboard');
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