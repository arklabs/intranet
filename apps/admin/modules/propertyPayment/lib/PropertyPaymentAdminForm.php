<?php

/**
 * propertyPayment admin form
 *
 * @package    intranet
 * @subpackage propertyPayment
 * @author     Your name here
 */
class PropertyPaymentAdminForm extends BasePropertyPaymentForm
{
  public function configure()
  {
    parent::configure();
    $context = dmContext::getInstance();
    $request = $context->getRequest();
    if ($this->isNew())
    {       
        if (is_array($default = $request->getParameter('defaults')) && array_key_exists('property_id',$default)){
            $property_id = $default['property_id'];
            $property = Doctrine::getTable('Property')->findById($property_id);
            if (!count($property)) die('Invalid Property ID ');
            $property = $property[0];

            $this->setWidget('property_id', new sfWidgetFormInputHidden(array(), array('value'=>$property->getId())));

            $this->setWidget('propiedad', new sfWidgetLinkTextWithToolTipForDiemBackend(array(
                                            'title'=> 'Si el sistema no logra autocompletar la propiedad que buscar, haga clic aqui para agergar una.',
                                            'url'=> $this->getHelper()->link('app:admin/+/property/edit')->params(array('pk'=>$property->getId()))->getHref(),
                                            'value'=>$property
                                         )));
            $this->setWidget('new_property', new sfWidgetFormInputHidden());
        }
        else {
            $this->setWidget('property_id', new sfWidgetFormDoctrineJQueryAutocompleter(array('model'=>'property','url'=>$this->getHelper()->link('app:admin/+/property/getJsonPropertyList')->getHref())));
            $this->setWidget('new_property', new sfWidgetLinkTextWithToolTipForDiemBackend(array(
                                                'title'=> 'Si el sistema no logra autocompletar la propiedad que buscar, haga clic aqui para agergar una.',
                                                'url'=> $this->getHelper()->link('app:admin/+/property/new')->getHref(),
                                                'value'=>'Propiedad'
                                             )));
            $this->setWidget('propiedad', new sfWidgetFormInputHidden());
        }
    }
    else
    {
        $this->setWidget('property_id', new sfWidgetFormInputHidden(array(), array('value'=>$this->getObject()->getPropertyId())));
        $this->setWidget('propiedad', new sfWidgetLinkTextWithToolTipForDiemBackend(array(
                                    'title'=> 'Clic para ver los detalles de esta propiedad',
                                    'url'=> $this->getHelper()->link('app:admin/+/property/edit')->params(array('pk'=>$this->getObject()->getProperty()->getId()))->getHref(),
                                    'value'=>$this->getObject()->getProperty()
                                )));
        $this->setWidget('new_property', new sfWidgetFormInputHidden(array(), array('value'=>$this->getObject()->getPropertyId())));

    }
    $this->getValidatorSchema()->setOption('allow_extra_fields', true); 
  }
}