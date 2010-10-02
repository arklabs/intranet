<?php
/* Variables:
 **  Event, agentList 
 */
if (count($event->getDmUser()) && $event->getEventStatus() != 'Pendiente'){
	$user = $event->getDmUser();
  	echo _tag('label.assigned', 'Asignado a '.$user[0].(($event->getEventStatus()!='Asignado' && $event->getEventStatus()!='Reasignar' && $event->getEventStatus()!='Reagendar')?' ('.$event->getEventStatus().')':''));
  	echo _tag('br');
} 
if (!count($event->getDmUser()) ||  $event->getEventStatus() == 'Pendiente' || $event->getEventStatus() == 'Reasignar'){
	$response = _tag('label.pending', $event->getEventStatus());
	$response.= _open('select.ev-asign-agent-list', array('id'=>$event->getId()));
	$response.= _open('option',array('value'=>-1)).'Seleccione un agente'._close('option');
	foreach ($agentList as $a){
		$response.= _open('option', array('value'=>$a->getId())).$a._close('option');
	}
	$response.= _close('select');
	$response.='<img src="/theme/images/loader-small.gif" style="display: none; float: right;" class="ev-status small-loader" id="'.$event->getId().'">';
	
}
if  ($event->getEventStatus() == 'Reagendar')
{
	$response.=_tag('label.pending', $event->getEventStatus());
	$dateStart= explode(' ',$event->getDateStart());
	$response.= _tag('label', '&nbsp;');
	$response.=_tag('a.reag-date rel="tipsy" href="#" title="Clic para cambiar esta fecha"', $dateStart[0]);
	$response.= _tag('label', '&nbsp;');
	$response.= '<input type="text"  readonly="true" class="reag-time" style="" value="'.$dateStart[1].'">';
	$response.= _tag('br');
	$response.= _open('a.fg-button.fg-button-icon-right.ui-widget.ui-state-default.ui-corner-all.apply-new-date', array('id'=>$event->getId(), 'tabindex'=>0, 'href'=>'#','style'=>'margin: 2px 0px;')).
	    		"&nbsp&nbsp;Aplicar".
				_close('a');
	$response.='<img src="/theme/images/loader-small.gif" style="display: none; float: right;" class="ev-status small-loader" id="'.$event->getId().'">';
}
echo $response;
