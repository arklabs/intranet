<?php

/**
 * client admin form
 *
 * @package    intranet
 * @subpackage client
 * @author     Your name here
 */
class ClientAdminForm extends BaseClientForm
{
  public function configure()
  {
      parent::configure();
      
      $this->widgetSchema['live_in'] = new sfWidgetFormDoctrineChoice(array('model'=>$this->getRelatedModelName('House'), 'add_empty' => false));
      $this->widgetSchema['source_id'] = new sfWidgetFormDoctrineChoice(array('model'=>$this->getRelatedModelName('Source'), 'add_empty' => false));
      $context = dmContext::getInstance();
      $request = $context->getRequest();
      $this->widgetSchema['live_in']->setOption('add_empty', true);
      if ($request->hasParameter('dm_embed'))
          $this->widgetSchema['assigned_to'] =  new sfWidgetFormInputHidden(array(), array('value'=>$context->getUser()->getDmUser()->getId()));
      else
          $this->widgetSchema['assigned_to'] = new sfWidgetFormDoctrineChoice(array('model'=>$this->getRelatedModelName('Agent'), 'add_empty' => false));
  }
  
  public function save($con = null)
  {
      if (!$this->isNew()){
          $this->values['created_by'] = $this->getObject()->getCreatedBy()->getId();
      }

      return parent::save($con);
  }
}