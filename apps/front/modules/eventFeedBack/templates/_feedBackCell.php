<?php
/*Var event*/
if (count($event->getEventFeedBack())){
echo _open('ul');
foreach ($event->getEventFeedBack() as $fb){
    echo _tag('li', $fb->getComments());
}
echo _close('ul');
}
else
    echo _tag('label', 'No tiene comentarios');
