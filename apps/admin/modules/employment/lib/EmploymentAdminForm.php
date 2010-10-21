<?php

/**
 * employment admin form
 *
 * @package    intranet
 * @subpackage employment
 * @author     Your name here
 */
class EmploymentAdminForm extends BaseEmploymentForm
{
  public function configure()
  {
    parent::configure();
    $context = dmContext::getInstance();
    $request = $context->getRequest();
    
    $years = range(0, 70);$years[0]= '';$years = array_combine($years, $years);
    $this->setWidget('years', new sfWidgetFormChoice(array('choices'=>$years)));
    $this->setValidator('years', new sfValidatorChoice(array('choices'=>range(1, 90), 'required'=>false)));
    if ($this->isNew())
        $this->setWidget('client_id', new sfWidgetFormDoctrineJQueryAutocompleter(array('model'=>'client','url'=>$this->getHelper()->link('app:admin/+/client/getJsonClientList')->getHref())));
    $this->setBackAndNewClientWidgets($request);
     $this->getValidatorSchema()->setOptions('allow_extra_fields', true);
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
      }elseif ((is_array($defaults) && $pk = $defaults['client_id']) || ($pk = $request->getParameter('pk'))){
          ;
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
      }else{
              $myClientWidget = new sfWidgetFormInputHidden();
              $myNewClientWidget = new sfWidgetLinkTextWithToolTipForDiemBackend(array(
                'title'=> 'Si el sistema no logra autocompletar el cliente que busca, haga clic aqui para agegarlo al sistema antes de agregar la nueva cita.',
                'url'=> $this->getHelper()->link('app:admin/+/client/new')->getHref(),
                'value'=>'Nuevo Cliente'
             ));
          }

      $this->setWidget('cliente', $myClientWidget);
      $this->setWidget('new_client', $myNewClientWidget);

      $this->setFancyDateTimeSelector();
      $this->setWidget('money_calculator', new arkMonthlyMoneyCalculator());
  }
  protected function setFancyDateTimeSelector($lock_dates = false){
    $this->setWidget('date_start', new sfWidgetFormInputHidden());
    $this->setWidget('date_end', new sfWidgetFormInputHidden());
    $this->setValidator('date_start', new sfValidatorRichDateTime(array('sf_date_format'=> "yyyy-MM-FF h:mm a", 'with_time'=>true)));
    $this->setValidator('date_end', new sfValidatorRichDateTime(array('sf_date_format'=> "yyyy-MM-FF h:mm a", 'with_time'=>true)));
    $this->setWidget('fancy_date_time', new arkCompleteJQueryDateTimePickerWidget(array('DateStartInputId'=>'#employment_admin_form_date_start', 'DateEndInputId'=>'#employment_admin_form_date_end', 'lock-dates'=>$lock_dates, 'SelectTimeStart'=>false, 'SelectTimeEnd'=>false,'HelpDateRange'=>'Haga clic en el calendario para seleccionar un d&iacute;a o rango de d&iacute;as. <br/>Seleccione un solo d&iacute;a en caso de que todav&iacute;a permanezca en el empleo.')));
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