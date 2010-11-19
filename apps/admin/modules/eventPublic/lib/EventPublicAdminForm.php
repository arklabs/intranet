<?php

/**
 * eventPublic admin form
 *
 * @package    intranet
 * @subpackage eventPublic
 * @author     Your name here
 */
class EventPublicAdminForm extends BaseEventPublicForm
{
  public function configure()
  {
    parent::configure();
    $context = dmContext::getInstance();
    if ($context->getUser()->hasPermission('editPublicCalendarEvents_front') || $context->getUser()->isSuperAdmin()){
  	$this->setFancyDateTimeSelector(0);
    }
    else
        $this->setFancyDateTimeSelector(1);
    $this->correctCategoryList();
  }

  public function  save($con = null) {
        $this->values['dm_user_id'] = DmUserTable::getPublicUser()->getId();
        $this->values['status_id'] = EventStatus::getAssignedStatus()->getId();
        return parent::save($con);
  }
  protected function correctCategoryList(){
      $result = array();
      foreach (Doctrine::getTable('EventCategory')->createQuery()->execute() as $ec){
          if ($ec->getName()!='Cita' && $ec->getName()!='Privado')
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
                'DateStartInputId'=>'#event_public_admin_form_date_start',
                'DateEndInputId'=>'#event_public_admin_form_date_end',
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