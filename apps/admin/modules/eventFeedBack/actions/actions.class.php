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
    protected function processForm(sfWebRequest $request, sfForm $form)
      {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid())
        {
          $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

          try {
            $event = $form->save();
            $this->getUser()->setFlash('notice', $notice);
            $redirection = $this->getRouteArrayForAction('edit', $event);
            $this->getUser()->setFlash('notice', $notice);
            $this->redirect($redirection);
            if ($request->hasParameter('dm_embed') && $request->hasParameter('dm_embed') == 1){
                echo '<script type="text/javascript"> if (parent.reload) parent.reload(); parent.$.fn.colorbox.close();</script>'; die;
            }
          } catch (Doctrine_Validator_Exception $e) {

            $errorStack = $form->getObject()->getErrorStack();

            $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ?  's' : null) . " with validation errors: ";
            foreach ($errorStack as $field => $errors) {
                $message .= "$field (" . implode(", ", $errors) . "), ";
            }
            $message = trim($message, ', ');

            $this->getUser()->setFlash('error', $message);
            return sfView::SUCCESS;
          }

          $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $event)));

          if ($request->hasParameter('_save_and_add'))
          {
            $this->getUser()->setFlash('notice', $notice.' You can add another one below.');

            $redirection = $this->getRouteArrayForAction('new');
          }
          elseif ($request->hasParameter('_save_and_list'))
          {
            $this->getUser()->setFlash('notice', $notice);

            $redirection = $this->getRouteArrayForAction('index');
          }
          elseif ($request->hasParameter('_save_and_next'))
          {
            $this->getUser()->setFlash('notice', $notice);
            $redirection = $this->getRouteArrayForAction('edit', dmArray::get($event->getPrevNextRecords($this->buildQuery()), 'next', $this->form->getObject()));
          }
          else
          {
            $this->getUser()->setFlash('notice', $notice);

            $redirection = $this->getRouteArrayForAction('edit', $event);
          }

          $this->redirect($redirection);
        }
        else
        {
          $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
        }
      }
}
