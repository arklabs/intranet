<?php

class dmWidgetGoogleMapShowForm extends dmWidgetPluginForm
{

  public function configure()
  {
    $this->widgetSchema['address'] = new sfWidgetFormInputText();
    $this->validatorSchema['address'] = new sfValidatorString();
    $this->widgetSchema['address']->setLabel('Search a place');

    $this->widgetSchema['mapTypeId'] = new sfWidgetFormSelect(array(
      'choices' => dmArray::valueToKey($this->getMapTypeIds())
    ));
    $this->validatorSchema['mapTypeId'] = new sfValidatorChoice(array(
      'choices' => $this->getMapTypeIds()
    ));
    $this->widgetSchema['mapTypeId']->setLabel('Map type');

    $this->widgetSchema['zoom'] = new sfWidgetFormSelect(array(
      'choices' => dmArray::valueToKey($this->getZooms())
    ));
    $this->validatorSchema['zoom'] = new sfValidatorChoice(array(
      'choices' => $this->getZooms()
    ));

    $this->widgetSchema['navigationControl'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['navigationControl'] = new sfValidatorBoolean();
    $this->widgetSchema['navigationControl']->setLabel('Navigation');

    $this->widgetSchema['mapTypeControl'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['mapTypeControl'] = new sfValidatorBoolean();
    $this->widgetSchema['mapTypeControl']->setLabel('Map type');

    $this->widgetSchema['scaleControl'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['scaleControl'] = new sfValidatorBoolean();
    $this->widgetSchema['scaleControl']->setLabel('Scale');

    $this->widgetSchema['width'] = new sfWidgetFormInputText(array(), array('size' => 5));
    $this->validatorSchema['width'] = new dmValidatorCssSize(array(
      'required' => false
    ));

    $this->widgetSchema['height'] = new sfWidgetFormInputText(array(), array('size' => 5));
    $this->validatorSchema['height'] = new dmValidatorCssSize(array(
      'required' => false
    ));

    $this->widgetSchema['splash'] = new sfWidgetFormInputText();
    $this->validatorSchema['splash'] = new sfValidatorString(array(
      'required' => false
    ));
    $this->widgetSchema->setHelp('splash', 'Display a message while the map is loading');

    if(!$this->getDefault('width'))
    {
      $this->setDefault('width', '100%');
    }
    if(!$this->getDefault('height'))
    {
      $this->setDefault('height', '300px');
    }
    if(!$this->getDefault('mapTypeId'))
    {
      $this->setDefault('mapTypeId', 'hybrid');
    }
    if(!$this->getDefault('zoom'))
    {
      $this->setDefault('zoom', '14');
    }

    parent::configure();
  }

  protected function getMapTypeIds()
  {
    return array('roadmap', 'satellite', 'hybrid', 'terrain');
  }

  protected function getZooms()
  {
    return range(2, 20);
  }

  public function getStylesheets()
  {
    return array(
      'lib.ui-tabs'
    );
  }

  public function getJavascripts()
  {
    return array(
      'lib.ui-tabs',
      'core.tabForm',
      'dmGoogleMapPlugin.widgetShowForm'
    );
  }

  protected function renderContent($attributes)
  {
    return $this->getHelper()->renderPartial('dmWidgetGoogleMap', 'showForm', array(
      'form' => $this,
      'baseTabId' => 'dm_widget_google_map_'.$this->dmWidget->get('id')
    ));
  }

  public function getWidgetValues()
  {
    $values = parent::getWidgetValues();
    
    return $values;
  }
}