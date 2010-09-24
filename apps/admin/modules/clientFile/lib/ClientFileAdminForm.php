<?php

/**
 * clientFile admin form
 *
 * @package    intranet
 * @subpackage clientFile
 * @author     Your name here
 */
class ClientFileAdminForm extends BaseClientFileForm
{
  protected function CorrectAvailableClientListForUser($user_id){
      $result = array();
      $clients = array();
      if (dmContext::getInstance()->getUser()->hasGroup('agentes'))
          $clients = Doctrine::getTable('Client')->getClientsAssignedTo(dmContext::getInstance()->getUser()->getDmUser()->getId())->execute();
      else
          $clients = Doctrine::getTable('Client')->createQuery()->execute();
      
      foreach ($clients as $client){
        $result[$client->getId()] = $client;
      }
      return $result;
  }

  public function configure()
  {
    parent::configure();
    $context = dmContext::getInstance();
    $request = $context->getRequest();
   
    $this->setWidget('date_start', new sfWidgetFormInputHidden());
    $this->setWidget('date_end', new sfWidgetFormInputHidden());

    $event_user_id = null;
    if (!$this->isNew()){
        $event_user_id = $this->getObject()->getAssignedTo();
        $this->setWidget('client_id', new sfWidgetFormInputHidden());
        $this->setWidget('client_link', new sfWidgetLinkTextWithToolTipForDiemBackend(array('title'=>'Ver los datos de este cliente', 'value'=>$this->getObject()->getClient(), 'url'=>$this->getHelper()->link('app:admin/+/client/edit')->params(array('pk'=>$this->getObject()->getClient()->getId(), 'dm_embed'=>$request->hasParameter('dm_embed')))->getHref())));
    }
    else {
	$this->setWidget('client_id', new sfWidgetFormDoctrineJQueryAutocompleter(array('model'=>'client','url'=>$this->getHelper()->link('app:front/+/client/getJsonClientList')->getHref())));
    	$event_user_id = $context->getUser()->getDmUser()->getId();
    	$this->setWidget('client_link', new sfWidgetFormInputHidden());
   	$this->getWidget('client_link')->setLabel(' ');
  	}
	
    $this->setWidget('fancy_date_time', new arkCompleteJQueryDateTimePickerWidget(array('DateStartInputId'=>'#client_file_admin_form_date_start', 'DateEndInputId'=>'#client_file_admin_form_date_end', 'lock-dates'=>(($this->isNew() || !$request->hasParameter('dm_embed') ||$event_user_id == $this->getObject()->getCreatedBy()->getId())?false:true))));
    
    $this->setValidator('date_start', new sfValidatorRichDateTime(array('sf_date_format'=> "yyyy-MM-FF h:mm a", 'with_time'=>true)));
    $this->setValidator('date_end', new sfValidatorRichDateTime(array('sf_date_format'=> "yyyy-MM-FF h:mm a", 'with_time'=>true)));
	
    if ($request->hasParameter('dm_embed') &&  $context->getUser()->hasGroup('agentes')){
	$this->setWidget('assigned_to', new sfWidgetFormInputHidden(array(), array('value'=>$event_user_id)));
    }
    if ($this->isNew()){
        
        $date = new sfDate($request->getParameter('date',time()));
        if ($request->hasParameter('date'));
            $date->addMonth(1);
        if ($request->getParameter('allDay', 'true') == 'true'){
            $date->clearTime();
        }
        $this->getWidget('date_start')->setAttribute('value', $date->dump());
        $this->getWidget('date_end')->setAttribute('value', $date->dump());
    }
    $this->getValidatorSchema()->setOption('allow_extra_fields', true);
  }
  public function save($con = null){
    if (!$this->isNew())	
        $this->values['created_by'] = $this->getObject()->getCreatedBy()->getId();
        return parent::save($con);
  }
}