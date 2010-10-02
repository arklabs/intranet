<?php

/**
 * incommingCallInventrary admin form
 *
 * @package    intranet
 * @subpackage incommingCallInventrary
 * @author     Your name here
 */
class IncommingCallInventraryAdminForm extends BaseIncommingCallInventraryForm
{
  public function configure()
  {
    parent::configure();
    $this->setWidget('agent_id', new sfWidgetFormDoctrineChoice(array('model'=>'Agent')));
    $this->setValidator('agent_id', new sfValidatorDoctrineChoice(array('model'=>'Agent')));
    $this->setWidget('property_id', new sfWidgetFormDoctrineChoice(array('model'=>'PropertyOnSell')));
    $this->setValidator('property_id', new sfValidatorDoctrineChoice(array('model'=>'PropertyOnSell')));
    $this->setWidget('reason_id', new sfWidgetFormDoctrineChoice(array('model'=>'IncommingCallReason')));
    $this->setValidator('reason_id', new sfValidatorDoctrineChoice(array('model'=>'IncommingCallReason')));
    $this->setWidget('caller_id', new sfWidgetFormDoctrineChoice(array('model'=>'IncommingCallUsualCallers')));
    $this->setValidator('caller_id', new sfValidatorDoctrineChoice(array('model'=>'IncommingCallUsualCallers')));

  }
}