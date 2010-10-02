<?php
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_javascript('check-incomming-call-menu-item');
use_javascript('enable-tooglable-fieldset');
echo _tag('h2.#dboard-toogle-trigger.fieldset_title.ui-helper-reset.collapsable-box-header style="text-align: left;width: 300px !important;"', '<span class="ui-icon ui-icon-triangle-1-e fieldset_icon"></span><span class="fieldset_name">Recepci&oacute;n</span>');
echo _open('div.fieldset_content.ui-helper-reset.ui-accordion-content-active.ui-helper-hidden.collapsable-box');
echo get_partial('main/genericDashboard', array('dashDescription'=>$dashDescription));
echo _close('div');
echo _open('div.clear', array('style', 'width:100%'));
echo _close('div');
?>
