<?php
    $statusControl = _open("select", array("name"=>"status")).
                    _open("option", array("value"=>"-1")).
                        "Cambiar de Estado..".
                    _close("option");
                    foreach ($availableStatus as $status){
                        $statusControl.= _open("option", array("value"=>$status->getId())).
                            $status->getName().
                        _close("option");
                    }
            $statusControl.= _close("select");
            $statusControl.= _open("a.button", array("style"=>"margin: 0px 10px 0px 10px; visibiliy: hidden;", "id"=>"apply","type"=>"submit"))
                            ."Aplicar"
                            ._close("a")
                            ._open("a.button.color-box-trigger", array("style"=>"margin: 0px 10px 0px 0px; visibiliy: hidden;","href"=>_link('app:admin/+/'.$sfModule.'/new')->params(array('dm_embed'=>1))->getHref()))
                                ."Nuevo"
                            ._close("a");
            echo $statusControl; 
?>
