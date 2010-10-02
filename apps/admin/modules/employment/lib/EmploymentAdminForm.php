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
  }
  
}