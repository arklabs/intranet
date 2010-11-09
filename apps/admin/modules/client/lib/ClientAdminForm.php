<?php

/**
 * client admin form
 *
 * @package    intranet
 * @subpackage client
 * @author     Your name here
 */
class ClientAdminForm extends BaseClientForm
{
  public function configure()
  {
      parent::configure();
      unset($this->widgetSchema['username']);
      unset($this->widgetSchema['password']);
      unset($this->widgetSchema['password_again']);
      unset($this->validatorSchema['username']);
      unset($this->validatorSchema['password']);
      unset($this->validatorSchema['password_again']);
      //$this->widgetSchema['live_in'] = new sfWidgetFormDoctrineChoice(array('model'=>$this->getRelatedModelName('House'), 'add_empty' => false));
      //$this->widgetSchema['live_in']->setOption('add_empty', true);
      $this->widgetSchema['source_id'] = new sfWidgetFormDoctrineChoice(array('model'=>$this->getRelatedModelName('Source'), 'add_empty' => false));
      $context = dmContext::getInstance();
      $request = $context->getRequest();
      
      if ($request->hasParameter('dm_embed'))
          $this->widgetSchema['assigned_to'] =  new sfWidgetFormInputHidden(array(), array('value'=>$context->getUser()->getDmUser()->getId()));
      else
          $this->widgetSchema['assigned_to'] = new sfWidgetFormDoctrineChoice(array('model'=>$this->getRelatedModelName('Agent'), 'add_empty' => false));

      $this->getValidator('email')->setOption('required', false);
      if (!$this->isNew() && $this->getObject()->getSsn()){
          $ssn = substr($this->getObject()->getSsn(), strlen($this->getObject()->getSsn()) - 4);
          $this->getWidget('ssn')->setAttribute('value', '************'. $ssn);
      }
      $this->setFancyDateTimeSelector();
  }
  
  public function save($con = null)
  {
      if (!$this->isNew()){
          $this->values['created_by'] = $this->getObject()->getCreatedBy()->getId();
          unset($this->values['ssn']);
      }
      else
      {
          $this->values['username'] = $username = DmUser::buildUsername($this->values['first_name'], $this->values['last_name']);
          $this->values['password'] = DmUser::buildPassword($username);
          if (!$this->values['email']){
              $this->values['email'] = $this->values['username'].'@no.given';
          }
      }

      return parent::save($con);
  }
  protected function setFancyDateTimeSelector($lock_dates = false){
    $this->setWidget('dob', new sfWidgetFormInputHidden());
    $this->setValidator('dob', new sfValidatorRichDateTime(array('required'=>false,'sf_date_format'=> "yyyy-MM-FF h:mm a", 'with_time'=>true)));
    $this->setWidget('fancy_date_time', new arkCompleteJQueryDateTimePickerWidget(array('DateStartInputId'=>'#client_admin_form_dob', 'DateEndInputId'=>'#client_admin_form_dob', 'lock-dates'=>0, 'HelpDateRange'=>'Haga clic en el calendario para seleccionar el d&iacute;a de nacimiento.', 'HelpTimeStart'=>'Hora inicio', 'HelpTimeEnd'=>'Hora fin','HelpGeneral'=>'Deje ambas horas en blanco en caso de ser un evento de todo el día.', 'SelectTimeStart'=>false, 'SelectTimeEnd'=>false, 'CalendarsNumber'=>1, 'CalendarMode'=>'single')));
    /*if ($this->isNew()){
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
    }*/
  }
}