<?php

/**
 * propertyOnSell admin form
 *
 * @package    intranet
 * @subpackage propertyOnSell
 * @author     Your name here
 */
class PropertyOnSellAdminForm extends BasePropertyOnSellForm
{
  public function configure()
  {
    parent::configure();
    $roomsValidator = range(1, 15); $rooms = array_combine($roomsValidator, $roomsValidator);
    $this->setWidget('rooms_number', new sfWidgetFormChoice(array('choices'=>$rooms)));
    $this->setValidator('rooms_number', new sfValidatorChoice(array('choices'=>$roomsValidator, 'required'=>false)));
    $this->setWidget('bath_rooms_number', new sfWidgetFormChoice(array('choices'=>$rooms)));
    $this->setValidator('bath_rooms_number', new sfValidatorChoice(array('choices'=>$roomsValidator, 'required'=>false)));

    $this->setWidget('property_type_id', new sfWidgetFormDoctrineChoice(array('model'=>'PropertyType')));
    $this->setValidator('property_type_id', new sfValidatorDoctrineChoice(array('model'=>'PropertyType')));
    $this->setWidget('agent_id', new sfWidgetFormDoctrineChoice(array('model'=>'Agent')));
    $this->setValidator('agent_id', new sfValidatorDoctrineChoice(array('model'=>'Agent')));
    $this->setWidget('ref_agent_id', new sfWidgetFormDoctrineChoice(array('model'=>'Agent')));
    $this->setValidator('ref_agent_id', new sfValidatorDoctrineChoice(array('model'=>'Agent')));
    $y = date('Y', time()); $pastYears =  range($y+1-100, $y); $pastYears[100] = '';$pastYears = array_reverse($pastYears);  $pastYears = array_combine($pastYears, $pastYears);
    $this->setWidget('year_built', new sfWidgetFormChoice(array('choices'=>$pastYears)));
    $this->setValidator('year_built', new sfValidatorChoice(array('choices'=>range($y-100, $y), 'required'=>false)));

    $this->embedAddressRelation();
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
}