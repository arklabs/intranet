<?php

/**
 * company admin form
 *
 * @package    intranet
 * @subpackage company
 * @author     Your name here
 */
class CompanyAdminForm extends BaseCompanyForm
{
  public function configure()
  {
    parent::configure();
    $this->setWidget('sector_id', new sfWidgetFormDoctrineChoice(array('model'=>'CompanySector')));
    $this->embedAddressRelation();
    $this->getValidator('Address')->setOption('required', false);
    $this->validatorSchema['Address']['address']->setOption('required', true);
    $this->validatorSchema['Address']['zip_code']->setOption('required', false);
  }
  protected function embedAddressRelation(){
      $prefix  = dmString::modulize($this->getModelName()).'_admin_form_Address_';
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
  public function  save($con = null) {
        if (!$this->values['Address']['address']){
                 unset($this->embeddedForms['Address']);
                 unset($this->values['Address']);
        }
        parent::save($con);
    }
}