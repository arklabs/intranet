<?php

require_once dirname(__FILE__).'/../lib/eventFeedBackGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/eventFeedBackGeneratorHelper.class.php';

/**
 * eventFeedBack actions.
 *
 * @package    intranet
 * @subpackage eventFeedBack
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class eventFeedBackActions extends autoEventFeedBackActions
{
    public function executeEdit(sfWebRequest $request)
    {
        parent::executeEdit($request);
        $eventId = $this->form->getObject()->getEventId();
        $this->getUser()->setAttribute('event_id', $eventId);
        $currentChoices = $this->form->getWidget('event_id')->getChoices();

        $myWidget = new sfWidgetLinkTextWithToolTipForDiemBackend(array(
            'title'=> 'Volver a este evento',
            'url'=> $this->generateUrl('event', array('pk'=>$eventId, 'action'=>'edit')),
            'value'=>$currentChoices[$eventId]
        ));

        $this->form->setWidget('event_id', new sfWidgetFormInputHidden());
        $this->form->setWidget('evento', $myWidget);
    }
    public function executeUpdate(sfWebRequest $request)
    {
        parent::executeUpdate($request);
        $eventId = $this->form->getObject()->getEventId();
        $this->getUser()->setAttribute('event_id', $eventId);
        $currentChoices = $this->form->getWidget('event_id')->getChoices();

        $myWidget = new sfWidgetLinkTextWithToolTipForDiemBackend(array(
            'title'=> 'Volver a este evento',
            'url'=> $this->generateUrl('event', array('pk'=>$eventId, 'action'=>'edit')),
            'value'=>$currentChoices[$eventId]
        ));

        $this->form->setWidget('event_id', new sfWidgetFormInputHidden());
        $this->form->setWidget('evento', $myWidget);
    }

    public function executeNew(sfWebRequest $request)
    {
        parent::executeNew($request);
        $defaults = $request->getParameter('defaults', $this->getUser()->getAttribute('event_id'));
        $eventId = (is_array($defaults))?$defaults['event_id']:$defaults;

        if (is_array($defaults)) // Si estas haciendo nuevo desde otro usuario actualiza las cookies para que no se pierda la accion el salvar y nuevo
            $this->getUser()->setAttribute('event_id', $eventId);

        $currentChoices = $this->form->getWidget('event_id')->getChoices();
        $myWidget = new sfWidgetLinkTextWithToolTipForDiemBackend(array(
            'title'=> 'Volver a este evento',
            'url'=> $this->generateUrl('event', array('pk'=>$eventId, 'action'=>'edit')),
            'value'=>$currentChoices[$eventId]
        ));

        $currentChoices = $this->form->getWidget('event_id')->getChoices();
        $this->form->setWidget('event_id', new sfWidgetFormInputHidden(array(), array('value'=>$eventId)));
        $this->form->setWidget('evento', $myWidget);
    }
}
