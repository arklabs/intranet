<?php

/**
 * referidos admin form
 *
 * @package    intranet
 * @subpackage referidos
 * @author     Your name here
 */
class ReferidosAdminForm extends BaseReferidosForm
{
  public function configure()
  {
    parent::configure();
    $this->setWidget('client_id', new sfWidgetFormDoctrineJQueryAutocompleter(array('model'=>'client','url'=>$this->getHelper()->link('app:front/+/client/getJsonClientList')->getHref())));
    $this->widgetSchema['city_id'] = new sfWidgetFormDoctrineChoice(array('model'=>$this->getRelatedModelName('City'), 'add_empty' => false));
  }
}