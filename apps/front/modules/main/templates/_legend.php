<?php
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('ark-icons-2-16');
use_javascript('enable-tooglable-fieldset');
echo _tag('h2.fieldset_title.ui-helper-reset.collapsable-box-header style="margin-left: 20px;"', '<span class="ui-icon ui-icon-triangle-1-e fieldset_icon"></span><span class="fieldset_name">Leyendas</span>');
echo _open('div.fieldset_content.ui-helper-reset.ui-accordion-content-active.ui-helper-hidden.collapsable-box');
foreach(array_keys($legends) as $legend_name)
    {
    echo _open('fieldset.legend');
    echo  _tag('legend',$legend_name);
    echo _open('ul');
    $i=0;
    foreach (array_keys($legends[$legend_name]) as $elem){
    	if (++$i > 4){
    	 	echo _close('ul')._open('ul');
    	 	$i=0;
    	}
        echo _tag('li style="min-height:18px;"',$elem.$legends[$legend_name][$elem]);
    }
    echo _close('ul');
    echo _close('fieldset');
}
echo _close('div');
 echo _open('div.clear', array('style', 'width:100%'));
 echo _close('div');
?>