<?php
/**
 * Client components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 * 
 */
class clientComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $userId = ($this->getUser()->getGuardUser())?$this->getUser()->getGuardUser()->getId():-1;
    $query = Doctrine::getTable('Client')->listClientsForUser($this->getUser()->getGuardUser());
    $this->clientPager = $this->getPager($query);
  }

  public function executeGeoClientsList()
  {
    $userId = ($this->getUser()->getGuardUser())?$this->getUser()->getGuardUser()->getId():-1;
    $query = Doctrine::getTable('Client')->listClientsForUser($this->getUser()->getGuardUser());
    $this->clientPager = $this->getPager($query);
  }

  public function executeSummary()
  {
     $clientId = $this->getRequestParameter('pk');
     $client = Doctrine::getTable('Client')->getSummary($clientId);
     
     $this->clientSummary = array(
        array( 
        	'partialModuleName'=>'main',
        	//'partialName'=>'genericListBox',
        	'partialName'=>'genericPlainTextListBox',
        	'data'=> $this->getGeneralInformationForGenericListBox($client),
     	),
     	array(
     		'partialModuleName'=>'main',
     		'partialName'=>'gClear',
        	'data'=> array()
     	),
     	array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getEmploymmentsGenericListData($client),
     		'attributes'=>'style="float:left;"'
     	),
     	array(
     		'partialModuleName'=>'main',
     		'partialName'=>'gClear',
        	'data'=> array()
     	),
     	array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getExpensesGenericListData($client),
     		'attributes'=>'style="float:left;"'
     	),
     	
     	array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getIncomeGenericListData($client),
     		'attributes'=>'style="float:left;"'
     	),
     	array(
     		'partialModuleName'=>'main',
     		'partialName'=>'gClear',
        	'data'=> array()
     	),
     	array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getAssetsGenericListData($client),
     		'attributes'=>'style="float:left;"'
     	),
     	array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getLiabilitiesGenericListData($client),
     		'attributes'=>'style="float:left;"'
     	),
     	array(
     		'partialModuleName'=>'main',
     		'partialName'=>'gClear',
        	'data'=> array()
     	)/*,
     	array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getPropertiesGenericListData($client),
     	) */
     );
     
     $this->client = $client; 
     
  }
  protected function getPropertiesGenericListData($client){
  	$data = array(
  	 			'showColumns' => array(
						          'Direccion'=>array('label'=>'Direcci&oacute;n', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
  	 							  //'Banco'=>array('label'=>'Banco', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
  								  'mortgage_payment'=>array('label'=>'Mortgage Payment', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
  								  'rent_amount_received'=>array('label'=>'Ingreso por Renta', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
  								  'is_the_property_being_modified'=>array('label'=>'Modificada anteriormente?', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
      							),
				'listArray'  => array(),
      			'boxTitle'=>'Listado de Propiedades'      							
      			);
  	 foreach ($client->getProperty() as $property){
  	 	array_push($data['listArray'], array(
              'Direccion' => $property,
  	 		  //'Banco'=>$property->getBank(),
  	 		  'mortgage_payment'=>$property->getMortgagePayment(),
  	 		  'rent_amount_received'=>$property->getRentAmountReceived(),
  	 		  'is_the_property_being_modified'=>__($property->getIsThePropertyBeingModified()),
          ));
  	 }
  	 return $data;
  }
  protected function getLiabilitiesGenericListData($client){
  	$data = array(
  	 			'showColumns' => array(
						          'Liability'=>array('label'=>'Liability', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
  	 							  'Pago Mensual'=>array('label'=>'Pago Mensual', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
  								  'Balance Due'=>array('label'=>'Pago Mensual', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
      							),
				'listArray'  => array(),
      			'boxTitle'=>'Listado de Liabilities'      							
      			);
  	 foreach ($client->getClientLiabilities() as $liability){
  	 	array_push($data['listArray'], array(
              'Liability' => $liability->getLiabilities(),
  	 		  'Pago Mensual'=>$liability->getMonthlyPayment(),
  	 		  'Balance Due'=>$liability->getBalanceDue(),
          ));
  	 }
  	 return $data;
  }
  protected function getAssetsGenericListData($client){
  	$data = array(
  	 			'showColumns' => array(
						          'Activo'=>array('label'=>'Ingreso', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
  	 							  'Valor Estimado'=>array('label'=>'Cantidad', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
      							),
				'listArray'  => array(),
      			'boxTitle'=>'Listado de Activos'      							
      			);
  	 foreach ($client->getClientAssets() as $asset){
  	 	array_push($data['listArray'], array(
              'Activo' => $asset->getAsset(),
  	 		  'Valor Estimado'=>$asset->getEstimatedValue(),
          ));
  	 }
  	 return $data;
  }
  protected function getIncomeGenericListData($client){
  	$data = array(
  	 			'showColumns' => array(
						          'Ingreso'=>array('label'=>'Ingreso', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
  	 							  'Cantidad'=>array('label'=>'Cantidad', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
						          'comments'=>array('label'=>'Comentarios', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
      							),
				'listArray'  => array(),
      			'boxTitle'=>'Listado de Ingresos'      							
      			);
  	 foreach ($client->getClientIncomes() as $income){
  	 	array_push($data['listArray'], array(
              'Ingreso' => $income->getIncome(),
  	 		  'Cantidad'=>$income->getAmount(),
              'comments' => $income->getComments(),
          ));
  	 }
  	 return $data;
  }
  protected function getExpensesGenericListData($client){
  		$data = array(
  	 			'showColumns' => array(
						          'Gasto'=>array('label'=>'Gasto', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
  	 							  'Cantidad'=>array('label'=>'Cantidad', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
						          'comments'=>array('label'=>'Comentarios', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
      							),
				'listArray'  => array(),
      			'boxTitle'=>'Listado de Gastos'      							
      			);
  	 foreach ($client->getClientExpenses() as $expense){
  	 	array_push($data['listArray'], array(
              'Gasto' => $expense->getExpense(),
  	 		  'Cantidad'=>$expense->getAmount(),
              'comments' => $expense->getComments(),
          ));
  	 }
  	 return $data;
  }
  
  protected function getGeneralInformationForGenericListBox($client){
  	 return array( 'listArray'=>array(
						     	array('label'=>'Nombre', 'value'=>$client, 'type'=>'string'),
						     	array('label'=>'Email', 'value'=>$client->getEmail(), 'type'=>'string'),
						     	array('label'=>'Tel&eacute;fono', 'value'=>$client->getPhone(), 'type'=>'string'),
						     	array('label'=>'Direcci&oacute;n', 'value'=>$client->getHouse(), 'type'=>'string'),
						     	array('label'=>'Creado por', 'value'=>$client->getCreatedBy(), 'type'=>'string'),
						     	array('label'=>'El d&iacute;a', 'value'=>$client->getCreatedAt(), 'type'=>'Date'),
						     	array('label'=>'Actualizado por', 'value'=>$client->getUpdatedBy(), 'type'=>'string'),
						     	array('label'=>'El d&iacute;a', 'value'=>$client->getUpdatedAt(), 'type'=>'Date'),
						     	array('label'=>'Asignado a', 'value'=>$client->getAgent().' ['.(($client->getAgent()->getPhone())?$client->getAgent()->getPhone().', ':'').$client->getAgent()->getEmail().']', 'type'=>'string')
		     				),
     				'boxTitle'=>'Informaci&oacute;n General'
	     	);
  }
  protected function getEmploymmentsGenericListData($client){
  	 $data = array(
  	 			'showColumns' => array(
						          'Company'=>array('label'=>'Compa&ntilde;&iacute;a', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
  	 							  'company_address'=>array('label'=>'Direcci&oacute;n', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
						          'title'=>array('label'=>'T&iacute;tulo', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
						          'phone'=>array('label'=>'Tel&eacute;fono', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
						          'start_date'=>array('label'=>'Inicio', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'Date'),
  	 							  'end_date'=>array('label'=>'Fin', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'Date'),
      							),
				'listArray'  => array(),
      			'boxTitle'=>'Listado de trabajos'      							
      			);
  	 foreach ($client->getEmployment() as $employment){
  	 	array_push($data['listArray'], array(
              'Company' => $employment->getCompany(),
  	 		  'company_address'=>$employment->getCompany()->getAddress(),
              'title' => $employment->getTitle(),
              'phone' => $employment->getPhone(),
  	 		  'start_date' => $employment->getStartDate(),
	          'end_date' => $employment->getEndDate()
          ));
  	 }
  	 return $data;
  }

}
