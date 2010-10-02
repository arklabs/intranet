<?php

/**
 * incommingCallProspect admin form
 *
 * @package    intranet
 * @subpackage incommingCallProspect
 * @author     Your name here
 */
class IncommingCallProspectAdminForm extends BaseIncommingCallProspectForm
{
  public function configure()
  {
    parent::configure();
    $this->setWidget('reason_id', new sfWidgetFormDoctrineChoice(array('model'=>'IncommingCallReason')));
    $this->setValidator('reason_id', new sfValidatorDoctrineChoice(array('model'=>'IncommingCallReason')));
    $this->setWidget('source_id', new sfWidgetFormDoctrineChoice(array('model'=>'Source')));
    $this->setValidator('source_id', new sfValidatorDoctrineChoice(array('model'=>'Source')));
  }
}