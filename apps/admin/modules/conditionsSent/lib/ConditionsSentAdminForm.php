<?php

/**
 * conditionsSent admin form
 *
 * @package    intranet
 * @subpackage conditionsSent
 * @author     Your name here
 */
class ConditionsSentAdminForm extends BaseConditionsSentForm
{
  public function configure()
  {
    parent::configure();
    $this->setWidget('client_id', new sfWidgetFormDoctrineJQueryAutocompleter(array('model'=>'client','url'=>$this->getHelper()->link('app:front/+/client/getJsonClientList')->getHref())));
    if ($this->isNew())
      $this->embedRelation('Property');
    else{
      $this->setWidget('Property', new sfWidgetFormInputHidden());
      $this->getValidatorSchema()->setOption('allow_extra_fields', true);
    }
  }
}