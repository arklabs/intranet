<?php

/**
 * clientIncome admin form
 *
 * @package    intranet
 * @subpackage clientIncome
 * @author     Your name here
 */
class ClientIncomeAdminForm extends BaseClientIncomeForm
{
  public function configure()
  {
    parent::configure();
    $context = dmContext::getInstance();
    $request = $context->getRequest();
    if ($this->isNew())
        $this->setWidget('client_id', new sfWidgetFormDoctrineJQueryAutocompleter(array('model'=>'client','url'=>$this->getHelper()->link('app:admin/+/client/getJsonClientList')->getHref())));
     $this->setBackAndNewClientWidgets($request);
     $this->setWidget('month_income', new arkMonthlyMoneyCalculator(array('choices'=>array('Hora'=>24*30,'Día'=>30,'Semana'=>4,'Mes'=>1,'Trimestre'=>1/3, 'Semestre'=>1/6, 'Año'=>1/12, ), 'default-key-choice'=>'Mes')));
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