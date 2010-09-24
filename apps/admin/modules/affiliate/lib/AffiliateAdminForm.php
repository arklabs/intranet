<?php

/**
 * affiliate admin form
 *
 * @package    intranet
 * @subpackage affiliate
 * @author     Your name here
 */
class AffiliateAdminForm extends BaseAffiliateForm
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