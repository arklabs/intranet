<?php

/**
 * property admin form
 *
 * @package    intranet
 * @subpackage property
 * @author     Your name here
 */
class PropertyAdminForm extends BasePropertyForm
{
  public function configure()
  {
    $context = dmContext::getInstance();
    $request = $context->getRequest();

    parent::configure();

    if ($this->isNew())
        $this->setWidget('client_id', new sfWidgetFormDoctrineJQueryAutocompleter(array('model'=>'client','url'=>$this->getHelper()->link('app:admin/+/client/getJsonClientList')->getHref())));

    $years = range(0, 90);$years[0]= '';$years = array_combine($years, $years);
    
    $people = range(0, 15); $people[0]='';$people=array_combine($people, $people);
    
    $y = date('Y', time()); $pastYears =  range($y+1-100, $y); $pastYears[100] = '';$pastYears = array_reverse($pastYears);  $pastYears = array_combine($pastYears, $pastYears);

    $roomsValidator = range(1, 15); $rooms = array_combine($roomsValidator, $roomsValidator);

    $this->setWidget('rooms_number', new sfWidgetFormChoice(array('choices'=>$rooms)));
    $this->setValidator('rooms_number', new sfValidatorChoice(array('choices'=>$roomsValidator, 'required'=>false)));
    $this->setWidget('bath_rooms_number', new sfWidgetFormChoice(array('choices'=>$rooms)));
    $this->setValidator('bath_rooms_number', new sfValidatorChoice(array('choices'=>$roomsValidator, 'required'=>false)));

    $this->setWidget('years_on_property', new sfWidgetFormChoice(array('choices'=>$years)));
    $this->setValidator('years_on_property', new sfValidatorChoice(array('choices'=>range(1, 90), 'required'=>false)));
            
    $this->setWidget('people_on_property', new sfWidgetFormChoice(array('choices'=>$people)));
    $this->setValidator('people_on_property', new sfValidatorChoice(array('choices'=>range(1, 15), 'required'=>false)));
            
    $this->setWidget('year_built', new sfWidgetFormChoice(array('choices'=>$pastYears)));
    $this->setValidator('year_built', new sfValidatorChoice(array('choices'=>range($y-100, $y), 'required'=>false)));


    $this->setWidget('modified_year', new sfWidgetFormChoice(array('choices'=>$pastYears)));
    $this->setValidator('modified_year', new sfValidatorChoice(array('choices'=>range($y-100, $y), 'required'=>false)));
    
    $this->setWidget('brought_year', new sfWidgetFormChoice(array('choices'=>$pastYears)));
    $this->setValidator('brought_year', new sfValidatorChoice(array('choices'=>range($y-100, $y), 'required'=>false)));

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
  }
}