<?php

/**
 * incommingCall admin form
 *
 * @package    intranet
 * @subpackage incommingCall
 * @author     Your name here
 */
class IncommingCallAdminForm extends BaseIncommingCallForm
{
  public function configure()
  {
    parent::configure();
    //$this->setWidget('dm_user_id', new sfWidgetFormDoctrineJQueryAutocompleter(array('model'=>'client','url'=>$this->getHelper()->link('app:admin/+/client/getJsonClientList')->getHref())));
  }
}