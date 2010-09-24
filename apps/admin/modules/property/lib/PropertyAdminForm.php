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
    parent::configure();
    $this->setWidget('House', new sfWidgetFormDinamicEmbeddedForm(array('form_model'=>'House', 'foreign_relation'=>'House', 'widget_name'=>'House'), array()));
  }
}