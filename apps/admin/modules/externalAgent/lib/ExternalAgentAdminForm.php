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
  }
  public function save($con = null){
  	$this->values['is_active'] = 0;
  	return parent::save($con);
  }
}