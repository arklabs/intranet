<?php

/**
 * address admin form
 *
 * @package    intranet
 * @subpackage address
 * @author     Your name here
 */
class AddressAdminForm extends BaseAddressForm
{
  public function configure()
  {
    parent::configure();
    $this->setWidget('zip_code', new sfWidgetFormDoctrineJQueryAutocompleter(array('model'=>'ZipCode','url'=>$this->getHelper()->link('app:admin/+/address/getZipCodeJsonList')->getHref())));
  }
}