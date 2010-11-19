<?php

/**
 * eventRoot admin form
 *
 * @package    intranet
 * @subpackage eventRoot
 * @author     Your name here
 */
class EventRootAdminForm extends BaseEventRootForm
{
  protected function eventIsAssignedToThisUser(){
      if ($this->isNew())
  		return false;
	$tmp = $this->getObject()->getDmUser();

	if (!count($tmp)) return false;
	$event_user_id = $tmp[0]->getId();
        $context = dmContext::getInstance();
	return ($context->getUser()->getDmUser()->getId()  == $event_user_id);
    }

  public function configure()
  {
    parent::configure();
    $context = dmContext::getInstance();
    if ($this->eventIsAssignedToThisUser() || $context->getUser()->isSuperAdmin()){
  	$this->setFancyDateTimeSelector(0);
    }
    else
        $this->setFancyDateTimeSelector(1);
    $this->correctCategoryList();
    $this->getValidator('title')->setOption('required', true);
    $this->getValidator('category_id')->setOption('required', true);
  }

  public function  save($con = null) {
       unset($this->values['property_id']);
       unset($this->values['phraseology_id']);
       unset($this->values['service_id']);
       unset($this->values['client_id']);
        $context = dmContext::getInstance();
        $this->values['dm_user_id'] = $context->getUser()->getDmUser()->getId();
        $this->values['status_id'] = EventStatus::getAssignedStatus()->getId();
        if (!$this->isNew()){
            $this->values['created_by'] = $this->getObject()->getCreatedBy();
        }
        return parent::save($con);
  }
  protected function correctCategoryList(){
      $result = array();
      foreach (Doctrine::getTable('EventCategory')->createQuery()->execute() as $ec){
          if ($ec->getName()!='Cita' && $ec->getName()!='Reunion')
            $result[$ec->getId()] = $ec->getName();
      }
      $this->setWidget('category_id', new sfWidgetFormChoice(array('choices'=>$result)));
  }
  protected function setFancyDateTimeSelector($lock_dates = false){
    $this->setWidget('date_start', new sfWidgetFormInputHidden());
    $this->setWidget('date_end', new sfWidgetFormInputHidden());
    $this->setValidator('date_start', new sfValidatorRichDateTime(array('sf_date_format'=> "yyyy-MM-FF h:mm a", 'with_time'=>true)));
    $this->setValidator('date_end', new sfValidatorRichDateTime(array('sf_date_format'=> "yyyy-MM-FF h:mm a", 'with_time'=>true)));
    $this->setWidget('fancy_date_time', new arkCompleteJQueryDateTimePickerWidget(
            array(
                'DateStartInputId'=>'#event_root_admin_form_date_start',
                'DateEndInputId'=>'#event_root_admin_form_date_end',
                'lock-dates'=>$lock_dates,
                'HelpDateRange'=>'Haga clic en el calendario para seleccionar un d&iacute;a o rango de d&iacute;as.',
                'HelpTimeStart'=>'Hora inicio',
                'HelpTimeEnd'=>'Hora fin',
                'HelpGeneral'=>'Deje ambas horas en blanco en caso de ser un evento de todo el día.'
                )
            ));
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
}