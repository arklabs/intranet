<?php

/**
 * incommingCallActiveMod admin form
 *
 * @package    intranet
 * @subpackage incommingCallActiveMod
 * @author     Your name here
 */
class IncommingCallActiveModAdminForm extends BaseIncommingCallActiveModForm
{
  public function configure()
  {
    parent::configure();
    parent::configure();
    $this->setWidget('agent_id', new sfWidgetFormDoctrineChoice(array('model'=>'Agent')));
    $this->setValidator('agent_id', new sfValidatorDoctrineChoice(array('model'=>'Agent')));
    $this->setWidget('property_id', new sfWidgetFormDoctrineChoice(array('model'=>'PropertyList')));
    $this->setValidator('property_id', new sfValidatorDoctrineChoice(array('model'=>'PropertyList')));
    $this->setWidget('reason_id', new sfWidgetFormDoctrineChoice(array('model'=>'IncommingCallReason')));
    $this->setValidator('reason_id', new sfValidatorDoctrineChoice(array('model'=>'IncommingCallReason')));
  }
}