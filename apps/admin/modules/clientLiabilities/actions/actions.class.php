<?php

require_once dirname(__FILE__).'/../lib/clientLiabilitiesGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/clientLiabilitiesGeneratorHelper.class.php';

/**
 * clientLiabilities actions.
 *
 * @package    intranet
 * @subpackage clientLiabilities
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class clientLiabilitiesActions extends autoClientLiabilitiesActions
{
  public function executeNew(sfWebRequest $request)
  {
    parent::executeNew($request);
    $defaults = $request->getParameter('defaults', $this->getUser()->getAttribute('client_id'));
    $clientId = (is_array($defaults))?$defaults['client_id']:$defaults;

    if (is_array($defaults)) // Si estas haciendo nuevo desde otro usuario actualiza las cookies para que no se pierda la accion el salvar y nuevo
            $this->getUser()->setAttribute('client_id', $clientId);

        $currentChoices = $this->form->getWidget('client_id')->getChoices();
        $myWidget = new sfWidgetLinkTextWithToolTipForDiemBackend(array(
            'title'=> 'Volver al Cliente',
            'url'=> $this->generateUrl('client', array('pk'=>$clientId, 'action'=>'edit')),
            'value'=>$currentChoices[$clientId]
        ));

        $currentChoices = $this->form->getWidget('client_id')->getChoices();
        $this->form->setWidget('client_id', new sfWidgetFormInputHidden(array(), array('value'=>$clientId)));
        $this->form->setWidget('client', $myWidget);
  }

  public function executeEdit(sfWebRequest $request)
    {
        parent::executeEdit($request);
        $clientId = $this->form->getObject()->getClientId();
        $this->getUser()->setAttribute('client_id', $clientId);
        $currentChoices = $this->form->getWidget('client_id')->getChoices();

        $myWidget = new sfWidgetLinkTextWithToolTipForDiemBackend(array(
            'title'=> 'Volver al Cliente',
            'url'=> $this->generateUrl('client', array('pk'=>$clientId, 'action'=>'edit')),
            'value'=>$currentChoices[$clientId]
        ));

        $this->form->setWidget('client_id', new sfWidgetFormInputHidden());
        $this->form->setWidget('client', $myWidget);
    }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

      try {
        $event = $form->save();
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
