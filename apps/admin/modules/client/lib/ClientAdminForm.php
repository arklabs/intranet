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
      unset($this->widgetSchema['username']);
      unset($this->widgetSchema['password']);
      unset($this->widgetSchema['password_again']);
      unset($this->validatorSchema['username']);
      unset($this->validatorSchema['password']);
      unset($this->validatorSchema['password_again']);
      //$this->widgetSchema['live_in'] = new sfWidgetFormDoctrineChoice(array('model'=>$this->getRelatedModelName('House'), 'add_empty' => false));
      //$this->widgetSchema['live_in']->setOption('add_empty', true);
      $this->widgetSchema['source_id'] = new sfWidgetFormDoctrineChoice(array('model'=>$this->getRelatedModelName('Source'), 'add_empty' => false));
      $context = dmContext::getInstance();
      $request = $context->getRequest();
      
      if ($request->hasParameter('dm_embed'))
          $this->widgetSchema['assigned_to'] =  new sfWidgetFormInputHidden(array(), array('value'=>$context->getUser()->getDmUser()->getId()));
      else
          $this->widgetSchema['assigned_to'] = new sfWidgetFormDoctrineChoice(array('model'=>$this->getRelatedModelName('Agent'), 'add_empty' => false));

      $this->getValidator('email')->setOption('required', false);
  }
  
  public function save($con = null)
  {
      if (!$this->isNew()){
          $this->values['created_by'] = $this->getObject()->getCreatedBy()->getId();
      }
      else
      {
          $this->values['username'] = $username = DmUser::buildUsername($this->values['first_name'], $this->values['last_name']);
          $this->values['password'] = DmUser::buildPassword($username);
          if (!$this->values['email']){
              $this->values['email'] = $this->values['email'].'@no.given';
          }
      }

      return parent::save($con);
  }
}