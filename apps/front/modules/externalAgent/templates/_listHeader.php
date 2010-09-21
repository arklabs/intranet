<?php
$statusControl.=_open("a.button.color-box-trigger", array("style"=>"margin: 0px 10px 0px 0px; visibiliy: hidden;","href"=>_link('app:admin/+/externalAgent/new')->params(array('dm_embed'=>1, 'incomming_call'=>1))->getHref()))
					."Nuevo"
				._close("a");
echo $statusControl; 

?>