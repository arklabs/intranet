<?php

/**
 * event admin form
 * Para el correcto funcionamiento de este formulario deben ponerse 
 * correctamente los permisos eventAssign, eventChangeDates
 * @package    intranet
 * @subpackage event
 * @author     Your name here
 */
class EventAdminForm extends BaseEventForm
{
  public static function getDefaultsDiemGroups(){
      return  array('developer', 'front_editor', 'integrator', 'seo', 'webmaster 1', 'webmaster_1', 'writer');
  }

  protected function isADefaultDiemGroup($groupName){
        return (array_search($groupName, self::getDefaultsDiemGroups()) > 0);
  }
   // Corrige la lista de usuarios disponibles para asignar un evento, habilitando las neuvas opciones adicionar a todos los agentes, adicionar a todos los telemarcadores. etcc..
  protected function correctAvailableUserList(){
      // getting all available groups
      $allGroups = Doctrine::getTable('DmGroup')->createQuery('g')->execute();
      $result = array(''=>'');
      foreach ($allGroups as $group){
         if (!$this->isADefaultDiemGroup($group->getName()))
            $result[-1*$group->getId()] = 'Todos los '.$group->getName();
      }

      foreach (Doctrine::getTable('DmUser')->createQuery()->execute() as $user){
        $result[$user->getId()] = $user;
      }
      return $result;
  }
  protected function setEventStatus(){
  }
  
  protected function configureGeneralFeatures(){
  	$context = dmContext::getInstance();
        $request = $context->getRequest();
  	 if (!$context->getUser()->hasPermission('assignDates_front') && !$context->getUser()->isSuperAdmin()){
            $this->setWidget('dm_user_id', new sfWidgetFormInputHidden());
            $this->getWidget('dm_user_id')->setLabel(' ');
         }
     elseif (!$this->isNew())
        	$this->getWidget('dm_user_id')->setAttribute('value',$context->getUser()->getDmUser()->getId());
  	 elseif ($this->isNew()) 
  	 {
		$eventAssignToChoices = $this->correctAvailableUserList();
                $this->setWidget('dm_user_id', new sfWidgetFormChoice(array('choices'=>$eventAssignToChoices, 'multiple'=>false, 'expanded'=>false)));
                $this->setValidator('dm_user_id', new sfValidatorAnd(array(new arkEmptyGroupValidator(),new sfValidatorChoice(array('choices'=>array_keys($eventAssignToChoices), 'multiple'=>false, 'required'=>false)))));
                $this->getValidator('dm_user_id')->setOption('required', false);
	 }
  	 if ($this->isNew() || ($this->thisUserOwnTheEvent() && $this->getObject()->getEventCategory()->getName() != 'Cita') || $context->getUser()->hasPermission('eventChangeDates') || $context->getUser()->isSuperAdmin()){
  	 	$this->setFancyDateTimeSelector(0);
  	 }
	 else
	  	$this->setFancyDateTimeSelector(1);

     $this->setWidget('service_id', new sfWidgetFormDoctrineChoice(array('model'=>'FileType')));
     $this->setWidget('phraseology_id', new sfWidgetFormDoctrineJQueryAutocompleter(array('model'=>'phraseology','url'=>$this->getHelper()->link('app:admin/+/phraseology/getPhraseologyJsonList')->getHref())));
     if ($this->isNew()){
	$this->setWidget('propiedad', new sfWidgetFormInputHidden());
        $this->setWidget('client_id', new sfWidgetFormDoctrineJQueryAutocompleter(array('model'=>'client','url'=>$this->getHelper()->link('app:admin/+/client/getJsonClientList')->getHref())));
        $this->setWidget('property_id', new sfWidgetFormDoctrineJQueryAutocompleter(array('model'=>'property','url'=>$this->getHelper()->link('app:admin/+/property/getJsonPropertyList')->getHref())));
        $this->setWidget('new_property', new sfWidgetLinkTextWithToolTipForDiemBackend(array(
                                            'title'=> 'Si el sistema no logra autocompletar la propiedad que buscar, haga clic aqui para agergar una.',
                                            'url'=> $this->getHelper()->link('app:admin/+/property/new')->getHref(),
                                            'value'=>'Propiedad'
                                         )));
     }
     else{
         $this->setWidget('new_property', new sfWidgetFormInputHidden());
		 $this->setWidget('property_id', new sfWidgetFormInputHidden(array(), array('value'=>$this->getObject()->getPropertyId())));
		 $this->setWidget('propiedad', new sfWidgetLinkTextWithToolTipForDiemBackend(array(
                                            'title'=> 'Clic para ver los detalles de esta propiedad',
                                            'url'=> $this->getHelper()->link('app:admin/+/property/edit')->params(array('pk'=>$this->getObject()->getProperty()->getId()))->getHref(),
                                            'value'=>$this->getObject()->getProperty()
                                         )));
	 }
     $this->getValidatorSchema()->setPostValidator(new arkValidateClientPropertyRelation());
     
     if ($this->isNew() || (!$this->eventIsAssignedToThisUser() && (!$context->getUser()->hasPermission('eventChangeStatus') && !$this->thisUserOwnTheEvent()) && !$context->getUser()->isSuperAdmin())){ // poniendo pendiente la nueva cita
  	 	$this->setWidget('status_id', new sfWidgetFormInputHidden());
  	 	if ($this->isNew()){
	  	    $this->getWidget('status_id')->setAttribute('value', Doctrine::getTable('EventStatus')->getDefaultValue());
  	 	}
		else 
			$this->getWidget('status_id')->setAttribute('value', $this->getObject()->getStatusId());
	 }
     $this->setCategoryWidget();
     $this->setBackAndNewClientWidgets($request);
     $this->validateDocumentList();
     $this->getValidatorSchema()->setOptions('allow_extra_fields', true);
     $this->embedAddressRelation();
     $this->getValidator('Address')->setOption('required', false);
     $this->getValidator('client_id')->setOption('required', true);
     $this->getValidator('property_id')->setOption('required', true);
     $this->getValidator('status_id')->setOption('required', true);
     $this->getValidator('service_id')->setOption('required', true);
     $this->validatorSchema['Address']['address']->setOption('required', false);
     $this->validatorSchema['Address']['zip_code']->setOption('required', false);
  }
  protected function setCategoryWidget(){
      $this->setWidget('category_id', new sfWidgetFormInputHidden());
      if ($this->isNew()){
          $result = array();
          foreach (Doctrine::getTable('EventCategory')->createQuery()->execute() as $ec){
              if ($ec->getName()!='Cita'){
                  $this->getWidget('category_id')->setAttribute('value', $ec->getId());
                  break;
              }
          }
      }
      
  }
  protected function embedAddressRelation(){
      $prefix  = dmString::underscore($this->getModelName()).'_admin_form_Address_';
      $this->getValidator('address_id')->setOption('required', false);
      $this->embedRelation('Address');
      $this->widgetSchema['Address']->setLabels(array(
          'address'=>'Dirección',
          'place_name'=>'Ciudad',
          'country_code'=>'País (abrv)',
          'state_code'=>'Estado (abrv)',
          'state_name'=>'Estado (nombre)',
      ));
      $this->widgetSchema['Address']['auto_fill_helper'] = new arkAddressAutoFillHelper(array(
                                                                        'zip_code_auto_complete_id'=>$prefix.'zip_code',
                                                                        'city_input_id'=>$prefix.'place_name',
                                                                        'pais_abr_input_id'=>$prefix.'country_code',
                                                                        'estate_abr_input_id'=>$prefix.'state_code',
                                                                        'estate_name_input_id'=>$prefix.'state_name'
                                                            ));
      $this->widgetSchema['Address']['auto_fill_helper']->setLabel(' ');
      //$this->widgetSchema['Address']['zip_code'] = new sfWidgetFormDoctrineJQueryAutocompleter(array('model'=>'ZipCode','url'=>$this->getHelper()->link('app:admin/+/address/getZipCodeJsonList')->getHref()));
  }
  public function correctPropertyList($clientId){
      $propertyList = Doctrine::getTable('Property')->getPropertiesOfClient($clientId);
      $choices = array();
      foreach ($propertyList as $prop){
            $choices[$prop->getId()] = $prop;
      }
      if ($this->isNew())
        $this->setWidget('property_id', new sfWidgetFormChoice(array('choices'=>$choices)));
      else
      {
        $this->setWidget('new_property', new sfWidgetFormInputHidden());
      }
      
  }
  protected function setBackAndNewClientWidgets($request){
      $defaults = $request->getParameter('defaults', null);
      if (!$this->isNew()){
          $pk = $this->getObject()->getClient()->getId();
          $myClientWidget = new sfWidgetLinkTextWithToolTipForDiemBackend(array(
            'title'=> 'Volver a este Cliente',
            'url'=> $this->getHelper()->link('app:admin/+/client/edit')->params(array('pk'=>$pk))->getHref(),
            'value'=>$this->getObject()->getClient()
         ));
         $myNewClientWidget = new sfWidgetFormInputHidden();
         $this->setWidget('client_id', new sfWidgetFormInputHidden());
         $this->correctPropertyList($pk);
      }elseif ((is_array($defaults) && $pk = $defaults['client_id']) || ($pk = $request->getParameter('pk'))){
          $client = Doctrine::getTable('Client')->findById($pk);
          if(count($client)){
              $myClientWidget = new sfWidgetLinkTextWithToolTipForDiemBackend(array(
                'title'=> 'Volver a este Cliente',
                'url'=> $this->getHelper()->link('app:admin/+/client/edit')->params(array('pk'=>$pk))->getHref(),
                'value'=>$client[0]
             ));
          }
          $myNewClientWidget = new sfWidgetFormInputHidden();
          $this->setWidget('client_id', new sfWidgetFormInputHidden());
          $this->correctPropertyList($pk);
      }else{
              $myClientWidget = new sfWidgetFormInputHidden();
              $myNewClientWidget = new sfWidgetLinkTextWithToolTipForDiemBackend(array(
                'title'=> 'Si el sistema no logra autocompletar el cliente que busca, haga clic aqui para agegarlo al sistema antes de agregar la nueva cita.',
                'url'=> $this->getHelper()->link('app:admin/+/client/new')->getHref(),
                'value'=>'Cliente'
             ));
          }
      
      $this->setWidget('cliente', $myClientWidget);
      $this->setWidget('new_client', $myNewClientWidget);
  }


  protected function configureFormForFront(){
  	 $this->configureGeneralFeatures();
  }
  
  protected function configureFormForBackend(){
  	 $this->configureGeneralFeatures();
  }
  
  protected function thisUserOwnTheEvent(){
  	if ($this->isNew()) 
  		return false;
	$tmp = $this->getObject()->getDmUser();

	if (!count($tmp)) return false;

	$event_user_id = $tmp[0]->getId();

	return ($this->getObject()->getCreatedBy()->getId() == $event_user_id);
  }

  protected function eventIsAssignedToThisUser(){
      if ($this->isNew())
  		return false;
	$tmp = $this->getObject()->getDmUser();

	if (!count($tmp)) return false;
	$event_user_id = $tmp[0]->getId();
        $context = dmContext::getInstance();
	return ($context->getUser()->getDmUser()->getId()  == $event_user_id);
  }
  
  protected function setFancyDateTimeSelector($lock_dates = false){
    $this->setWidget('date_start', new sfWidgetFormInputHidden());
    $this->setWidget('date_end', new sfWidgetFormInputHidden());
    $this->setValidator('date_start', new sfValidatorRichDateTime(array('sf_date_format'=> "yyyy-MM-FF h:mm a", 'with_time'=>true)));
    $this->setValidator('date_end', new sfValidatorRichDateTime(array('sf_date_format'=> "yyyy-MM-FF h:mm a", 'with_time'=>true)));
    $this->setWidget('fancy_date_time', new arkCompleteJQueryDateTimePickerWidget(array('DateStartInputId'=>'#event_admin_form_date_start', 'DateEndInputId'=>'#event_admin_form_date_end', 'lock-dates'=>$lock_dates, 'HelpDateRange'=>'Haga clic en el calendario para seleccionar un d&iacute;a o rango de d&iacute;as.', 'HelpTimeStart'=>'Hora inicio', 'HelpTimeEnd'=>'Hora fin','HelpGeneral'=>'Deje ambas horas en blanco en caso de ser un evento de todo el día.', 'SelectTimeEnd'=>false)));
    if ($this->isNew()){
    	$context = dmContext::getInstance();
    	$request = $context->getRequest();
        $date = new sfDate($request->getParameter('date',time()));
        if ($request->hasParameter('date'))
            $date->addMonth(1);
        if ($request->getParameter('allDay', 'true') == 'true'){
            $date->clearTime();
        }
        $this->getWidget('date_start')->setAttribute('value', $date->dump());
        $this->getWidget('date_end')->setAttribute('value', $date->dump());
    }
  }

  protected function validateDocumentList(){
      $context = dmContext::getInstance();
      if (!$context->getUser()->hasPermission('EventCollectDocuments') && !($context->getUser()->isSuperAdmin())){
          $this->setWidget('document_list', new sfWidgetFormInputHidden());
          $this->setValidator('document_list', new sfValidatorPass());
          $this->setWidget('event_feed_back_list', new sfWidgetFormInputHidden());

      }
          
  }
  public function configure()
  {
    parent::configure();
    $context = dmContext::getInstance();
    $request = $context->getRequest();
    if ($request->hasParameter('dm_embed'))
    	$this->configureFormForFront();
    else 
    	$this->configureFormForBackend();
    //$this->setWidget('dm_user_id', new sfWidgetLinkTextWithToolTipForDiemBackend(array('url'=>"#", 'value'=>$this->getObject()->DmUser[0], 'title'=>"Usuario al que se le ha asignado este evento."), array('value'=>'1', 'name'=>'event_admin_form[dm_use_id]')));
  }

  public function  save($con = null) {
         if (!$this->values['Address']['address']){
                 unset($this->embeddedForms['Address']);
                 unset($this->values['Address']);
         }

	  if (!$this->isNew() && dmContext::getInstance()->getRequest()->hasParameter('dm_embed') && dmContext::getInstance()->getUser()->getDmUser()->getId() != $this->getObject()->getCreatedBy()->getId() ){ // vienes del frontend y quieres modificar un evento que no es tuyo
        $this->values = array(
            'id'=>$this->values['id'],
            'status_id'=>$this->values['status_id'],
            'client_id'=>$this->values['client_id'],
            'document_list'=>$this->values['document_list'],
        );
        dmContext::getInstance()->getUser()->setFlash('notice', 'Solo toman efecto los cambios de estado.');
      }
	  if ($this->values['dm_user_id']!=dmContext::getInstance()->getUser()->getDmUser()->getId())
            $this->values['is_new'] = 1;
      $this->values['is_active'] = 1;
      if (!$this->isNew()){
          $this->values['created_by'] = $this->getObject()->getCreatedBy()->getId();
          $this->values['is_new'] = 0;
      }
      // for multiple assignation purposes 
      if ($this->isNew() && !dmContext::getInstance()->getRequest()->hasParameter('dm_embed')){
          if ($this->values['dm_user_id'] < 0){
              return $this->saveMultipleAssignation();
          }
      }
	  //echo $this->values['created_by'];
	  if ($this->isNew() && dmContext::getInstance()->getUser()->getDmUser()->getId() == $this->values['created_by']){
		//	 autoassignar cita 
		$this->values['dm_user_id'] = dmContext::getInstance()->getUser()->getDmUser()->getId();
		
		$eventStatusAssigned = Doctrine::getTable('EventStatus')->findByName('Asignado');
		if (count($eventStatusAssigned))
			$this->values['status_id'] = $eventStatusAssigned[0]->getId();
	  }
      return parent::save($con);

  }
  protected function saveMultipleAssignation(){
  	// getting the group
              $groupId = -1*$this->values['dm_user_id'];
              $group = Doctrine::getTable('DmGroup')->findById($groupId);
              $first = null;
              foreach ($group[0]->getUsers() as $u){
                $event = Doctrine::getTable('Event')->create($this->values);
                $event->setDmUserId($u->getId());
                $event->save();
                if (!$first)
                    $first = $event;
              }
              return $first;
  	
  }
}
