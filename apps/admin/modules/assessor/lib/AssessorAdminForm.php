<?php

/**
 * assessor admin form
 *
 * @package    intranet
 * @subpackage assessor
 * @author     Your name here
 */
class AssessorAdminForm extends BaseAssessorForm
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
      //$this->setWidget($name, $widget)
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