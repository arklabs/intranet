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
    $this->setWidget('property_type_id', new sfWidgetFormDoctrineChoice(array('model'=>'PropertyType')));
    $this->setValidator('property_type_id', new sfValidatorDoctrineChoice(array('model'=>'PropertyType')));
    $this->setWidget('agent_id', new sfWidgetFormDoctrineChoice(array('model'=>'Agent')));
    $this->setValidator('agent_id', new sfValidatorDoctrineChoice(array('model'=>'Agent')));
    $this->setWidget('ref_agent_id', new sfWidgetFormDoctrineChoice(array('model'=>'Agent')));
    $this->setValidator('ref_agent_id', new sfValidatorDoctrineChoice(array('model'=>'Agent')));
    $y = date('Y', time()); $pastYears =  range($y+1-100, $y); $pastYears[100] = '';$pastYears = array_reverse($pastYears);  $pastYears = array_combine($pastYears, $pastYears);
    $this->setWidget('year_built', new sfWidgetFormChoice(array('choices'=>$pastYears)));
    $this->setValidator('year_built', new sfValidatorChoice(array('choices'=>range($y-100, $y), 'required'=>false)));


  }
}