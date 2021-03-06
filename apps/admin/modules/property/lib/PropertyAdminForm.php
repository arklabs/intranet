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

    $roomsValidator = range(1, 7); $rooms = array_combine($roomsValidator, $roomsValidator);

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

    $this->setWidget('property_use_id', new sfWidgetFormDoctrineChoice(array('model'=>'PropertyUse', 'add_empty'=>true)));
    $this->setValidator('property_use_id', new sfValidatorDoctrineChoice(array('model'=>'PropertyUse')));

    $this->embedAddressRelation();
    $this->getValidator('client_id')->setOption('required', true);
    $this->setBackAndNewClientWidgets($request);
    $this->getValidatorSchema()->setOptions('allow_extra_fields', true);
  }

  protected function embedAddressRelation(){
      $prefix  = dmString::underscore($this->getModelName()).'_admin_form_Address_';
      $this->embedRelation('Address');
      $this->getValidator('address_id')->setOption('required', false);
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