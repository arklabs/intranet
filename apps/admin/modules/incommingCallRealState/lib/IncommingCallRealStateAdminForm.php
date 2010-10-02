<?php

/**
 * incommingCallRealState admin form
 *
 * @package    intranet
 * @subpackage incommingCallRealState
 * @author     Your name here
 */
class IncommingCallRealStateAdminForm extends BaseIncommingCallRealStateForm
{
  public function configure()
  {
    parent::configure();
    $this->setWidget('agent_id', new sfWidgetFormDoctrineChoice(array('model'=>'Agent')));
    $this->setValidator('agent_id', new sfValidatorDoctrineChoice(array('model'=>'Agent')));
    $this->setWidget('caller_id', new sfWidgetFormDoctrineChoice(array('model'=>'IncommingCallUsualCallers')));
    $this->setValidator('caller_id', new sfValidatorDoctrineChoice(array('model'=>'IncommingCallUsualCallers')));
    $this->setWidget('file_type_id', new sfWidgetFormDoctrineChoice(array('model'=>'FileType')));
    $this->setValidator('file_type_id', new sfValidatorDoctrineChoice(array('model'=>'FileType')));
    
  }
}