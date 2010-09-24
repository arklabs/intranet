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
  }
  public function save($con = null){
  	$this->values['is_active'] = 0;
  	return parent::save($con);
  }
}