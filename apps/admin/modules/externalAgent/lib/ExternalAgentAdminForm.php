<?php

/**
 * externalAgent admin form
 *
 * @package    intranet
 * @subpackage externalAgent
 * @author     Your name here
 */
class ExternalAgentAdminForm extends BaseExternalAgentForm
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
    
    $this->setWidget('extag_company_id', new sfWidgetFormDoctrineChoice(array('model'=>'Company')));
    $this->setWidget('extag_source_id', new sfWidgetFormDoctrineChoice(array('model'=>'Source')));
    $this->setValidator('extag_company_id', new sfValidatorDoctrineChoice(array('model'=>'Company')));
    $this->setValidator('extag_source_id', new sfValidatorDoctrineChoice(array('model'=>'Source')));
  }
  public function save($con = null){
  	$this->values['is_active'] = 0;
        if ($this->isNew())
          {
              $this->values['username'] = $username = DmUser::buildUsername($this->values['first_name'], $this->values['last_name']);
              $this->values['password'] = DmUser::buildPassword($username);
          }
  	return parent::save($con);
  }
}