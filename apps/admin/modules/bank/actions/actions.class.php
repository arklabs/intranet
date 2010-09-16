<?php

require_once dirname(__FILE__).'/../lib/bankGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/bankGeneratorHelper.class.php';

/**
 * bank actions.
 *
 * @package    intranet
 * @subpackage bank
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class bankActions extends autoBankActions
{
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
