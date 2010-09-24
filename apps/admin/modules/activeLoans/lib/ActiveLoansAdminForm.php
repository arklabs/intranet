<?php

/**
 * activeLoans admin form
 *
 * @package    intranet
 * @subpackage activeLoans
 * @author     Your name here
 */
class ActiveLoansAdminForm extends BaseActiveLoansForm
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