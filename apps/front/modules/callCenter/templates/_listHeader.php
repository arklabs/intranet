<?php use_stylesheet('ark-icons-1-16');?>
<?php
echo _open('a.bt-flat.fg-button.button.ui-corner-all', array('tabindex'=>0, 'href'=>'#'));
echo "Nuevo";
echo _close('a');
echo _open('div.hidden');
    echo _open('ul');
    echo _tag('li', sprintf('<a href="%s"  class="color-box-trigger ark-clear-left"><!--<span class="ark-icon-1-16 ark-icon-insert-client-file icon-fl"></span>--> %s </a>',_link('app:admin/+/client/new')->params(array('dm_embed'  => 1))->getHref(), "Cliente"));
    echo _tag('li', sprintf('<a href="%s"  class="color-box-trigger ark-clear-left"><!--<span class="ark-icon-1-16 ark-icon-insert-client-file icon-fl"></span>--> %s </a>',_link('app:admin/+/house/new')->params(array('dm_embed'  => 1))->getHref(), "Vivienda"));
    echo _tag('li', sprintf('<a href="%s"  class="color-box-trigger ark-clear-left"><!--<span class="ark-icon-1-16 ark-icon-insert-client-file icon-fl"></span>--> %s </a>',_link('app:admin/+/bank/new')->params(array('dm_embed'  => 1))->getHref(), "Banco"));
    echo _tag('li', sprintf('<a href="%s"  class="color-box-trigger ark-clear-left"><!--<span class="ark-icon-1-16 ark-icon-insert-client-file icon-fl"></span>--> %s </a>',_link('app:admin/+/property/new')->params(array('dm_embed'  => 1))->getHref(), "Propiedad"));
    echo _tag('li', sprintf('<a href="%s"  class="color-box-trigger ark-clear-left"><!--<span class="ark-icon-1-16 ark-icon-insert-client-file icon-fl"></span>--> %s </a>',_link('app:admin/+/referidos/new')->params(array('dm_embed'  => 1))->getHref(), "Referidos"));
    echo _tag('li', sprintf('<a href="%s"  class="color-box-trigger ark-clear-left"><!--<span class="ark-icon-1-16 ark-icon-insert-client-file icon-fl"></span>--> %s </a>',_link('app:admin/+/activeListing/new')->params(array('dm_embed'  => 1))->getHref(), "Active Listing"));
    echo _tag('li', sprintf('<a href="%s"  class="color-box-trigger ark-clear-left"><!--<span class="ark-icon-1-16 ark-icon-insert-client-file icon-fl"></span>--> %s </a>',_link('app:admin/+/activeMods/new')->params(array('dm_embed'  => 1))->getHref(), "Active Mods"));
    echo _tag('li', sprintf('<a href="%s"  class="color-box-trigger ark-clear-left"><!--<span class="ark-icon-1-16 ark-icon-insert-client-file icon-fl"></span>--> %s </a>',_link('app:admin/+/activeLoans/new')->params(array('dm_embed'  => 1))->getHref(), "Active Loans"));
    echo _tag('li', sprintf('<a href="%s"  class="color-box-trigger ark-clear-left"><!--<span class="ark-icon-1-16 ark-icon-insert-client-file icon-fl"></span>--> %s </a>',_link('app:admin/+/conditionsSent/new')->params(array('dm_embed'  => 1))->getHref(), "Conditions Sent"));
    echo _close('ul');
echo _close('div');