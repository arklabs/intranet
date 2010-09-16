<?php

class dmWidgetContentLinkForm extends dmWidgetPluginForm
{

  public function configure()
  {
    $this->widgetSchema['href']     = new sfWidgetFormInputText(array(), array(
      'class' => 'dm_link_droppable',
      'title' => $this->__('Accepts pages, medias and urls')
    ));
    
    $this->validatorSchema['href']  = new dmValidatorLinkUrl(array('required' => true));
    
    $this->widgetSchema['text']     = new sfWidgetFormTextarea(array(), array('rows' => 2));
    $this->validatorSchema['text']  = new sfValidatorString(array('required' => false));

    $this->widgetSchema['title']    = new sfWidgetFormInputText();
    $this->validatorSchema['title'] = new sfValidatorString(array('required' => false));

    parent::configure();
  }

}