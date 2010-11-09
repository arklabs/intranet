<?php
/**
 * Client components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
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
     //$clientId = $this->getRequestParameter('pk');
     //$client = Doctrine::getTable('Client')->getSummary($clientId);
     /*$query = $this->getShowQuery();

     $this->client = $this->getRecord($query);
     $client = Doctrine::getTable('Client')->getSummary($this->client->getId());

     $this->clientSummary = array(
        array(
        	'partialModuleName'=>'main',
        	//'partialName'=>'genericListBox',
        	'partialName'=>'genericPlainTextListBox',
        	'data'=> $this->getGeneralInformationForGenericListBox($client),
                'attributes'=>'style="margin-left: 10px;"',
     	),
     	array(
     		'partialModuleName'=>'main',
     		'partialName'=>'gClear',
        	'data'=> array(),
     	),
     	array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getEmploymmentsGenericListData($client),
     		'attributes'=>'style="float:left;margin:10px;"'
     	),
        array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getAssetsGenericListData($client),
     		'attributes'=>'style="float:left;margin:10px;"'
     	),
     	array(
     		'partialModuleName'=>'main',
     		'partialName'=>'gClear',
        	'data'=> array(),
                'attributes="margin: 10px;"',
     	),
     	array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getExpensesGenericListData($client),
     		'attributes'=>'style="float:left;margin:10px;"'
     	),
     	
     	array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getIncomeGenericListData($client),
     		'attributes'=>'style="float:left;margin:10px;"'
     	),
     	array(
     		'partialModuleName'=>'main',
     		'partialName'=>'gClear',
        	'data'=> array()
     	),
     	
     	array(
     		'partialModuleName'=>'main',
     		'partialName'=>'gClear',
        	'data'=> array(),
                'attributes="margin: 10px;"',
     	)/*,
     	array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getPropertiesGenericListData($client),
     	) 
     );
     
     $this->client = $client;
      * */
  }


  protected function getLiabilitiesGenericListData($client)
  {
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

  protected function getAssetsGenericListData($client)
  {
    $data = array(
    			'showColumns' => array(
    		          'Activo'=>array('label'=>'Activo', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
    						  'Valor Estimado'=>array('label'=>'Valor Estimado', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
      							),
    'listArray'  => array(),
      			'boxTitle'=>'Listado de Activos'      							
      			);
    foreach ($client->getClientAsset() as $asset){
    	array_push($data['listArray'], array(
              'Activo' => $asset->getAsset(),
    		  'Valor Estimado'=>$asset->getEstimatedValue(),
          ));
    }
    return $data;
  }

  protected function getIncomeGenericListData($client)
  {
    $data = array(
    			'showColumns' => array(
    		          'Ingreso'=>array('label'=>'Ingreso', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
    					          'Cantidad'=>array('label'=>'Cantidad', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
    		          'comments'=>array('label'=>'Comentarios', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
      							),
    'listArray'  => array(),
      			'boxTitle'=>'Listado de Ingresos'      							
      			);
    foreach ($client->getClientIncome() as $income){
    	array_push($data['listArray'], array(
              'Ingreso' => $income->getIncome(),
    		  'Cantidad'=>$income->getMonthIncome(),
              'comments' => $income->getComments(),
          ));
    }
    return $data;
  }

  protected function getExpensesGenericListData($client)
  {
    $data = array(
    			'showColumns' => array(
    		          'Gasto'=>array('label'=>'Gasto', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
    						  'Cantidad'=>array('label'=>'Cantidad', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
    						  'Pago'=>array('label'=>'Pago', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
    						  'Amortizado por'=>array('label'=>'Amortizado por', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
    		          'comments'=>array('label'=>'Comentarios', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
      							),
    'listArray'  => array(),
      			'boxTitle'=>'Listado de Gastos'      							
      			);
    foreach ($client->getClientExpense() as $expense){
    	array_push($data['listArray'], array(
              'Gasto' => $expense->getExpense(),
              'Cantidad'=>$expense->getAmount(),
              'Pago'=>$expense->getPayment(),
              'Amortizado por'=>$expense->getPropertyLoanTime().' A&ntilde;os',
              'comments' => $expense->getComments(),
          ));
    }
    return $data;
  }

  protected function getGeneralInformationForGenericListBox($client)
  {
    return array( 'listArray'=>array(
                                                array('label'=>'Nombre', 'value'=>$client, 'type'=>'string'),
                                                array('label'=>'Email', 'value'=>$client->getEmail(), 'type'=>'string'),
                                                array('label'=>'Cel', 'value'=>$client->getPhone(), 'type'=>'string'),
                                                array('label'=>'Casa-tel', 'value'=>$client->getHomePhone(), 'type'=>'string'),
                                                array('label'=>'Creado por', 'value'=>$client->getCreatedBy(), 'type'=>'string'),
                                                array('label'=>'El d&iacute;a', 'value'=>$client->getCreatedAt(), 'type'=>'Date'),
                                                array('label'=>'Actualizado por', 'value'=>$client->getUpdatedBy(), 'type'=>'string'),
                                                array('label'=>'El d&iacute;a', 'value'=>$client->getUpdatedAt(), 'type'=>'Date')
                                         ),
     				'boxTitle'=>'Informaci&oacute;n General'
      	);
  }

  protected function getEmploymmentsGenericListData($client)
  {
    $data = array(
    			'showColumns' => array(
    		          'Company'=>array('label'=>'Compa&ntilde;&iacute;a', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
    		          'ocupation'=>array('label'=>'Ocupaci&oacute;n', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
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
              'ocupation' => $employment->getOcupation(),
              'start_date' => $employment->getDateStart(),
       'end_date' => $employment->getDateEnd()
          ));
    }
    return $data;
  }

  public function getPropertiesGenericListData($client){
      $data = array(
    			'showColumns' => array(
    		          'address'=>array('label'=>'Direcci&oacute;n', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
    		          'parcel'=>array('label'=>'Parcela', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
                          'lote'=>array('label'=>'Lote', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
    		          'rooms'=>array('label'=>'Cuartos', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
    		          'baths'=>array('label'=>'Ba&ntilde;os', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
    		          'tipo'=>array('label'=>'Tipo', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
    		          'hipotecas'=>array('label'=>'# Hipotecas', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false)
      							),
    'listArray'  => array(),
      			'boxTitle'=>'Listado de Propiedades'
      			);
    foreach ($client->getProperty() as $property){
    	array_push($data['listArray'], array(
              'address' => $property->getAddress(),
              'parcel'=>$property->getParcel(),
              'rooms'=>$property->getRoomsNumber(),
              'baths'=>$property->getBathRoomsNumber(),
              'sqft'=>$property->getSqft(),
              'lote'=>$property->getLote(),
              'tipo'=>$property->getPropertyType(),
              'hipotecas'=>count($property->getPropertyPayment())
          ));
    }
    return $data;
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->client = $this->getRecord($query);
     $client = Doctrine::getTable('Client')->getSummary($this->client->getId());
     $this->clientSummary = array(
        array(
        	'partialModuleName'=>'main',
        	//'partialName'=>'genericListBox',
        	'partialName'=>'genericPlainTextListBox',
        	'data'=> $this->getGeneralInformationForGenericListBox($client),
                'attributes'=>'style="margin-left: 10px;"',
     	),
     	array(
     		'partialModuleName'=>'main',
     		'partialName'=>'gClear',
        	'data'=> array(),
     	),
     	array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getEmploymmentsGenericListData($client),
     		'attributes'=>'style="float:left;margin:10px;"'
     	),
        array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getAssetsGenericListData($client),
     		'attributes'=>'style="float:left;margin:10px;"'
     	),
     	array(
     		'partialModuleName'=>'main',
     		'partialName'=>'gClear',
        	'data'=> array(),
                'attributes="margin: 10px;"',
     	),
     	array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getExpensesGenericListData($client),
     		'attributes'=>'style="float:left;margin:10px;"'
     	),

     	array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getIncomeGenericListData($client),
     		'attributes'=>'style="float:left;margin:10px;"'
     	),
     	array(
     		'partialModuleName'=>'main',
     		'partialName'=>'gClear',
        	'data'=> array()
     	),
        array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getPropertiesGenericListData($client),
     		'attributes'=>'style="float:left;margin:10px;"'
     	),
     	array(
     		'partialModuleName'=>'main',
     		'partialName'=>'gClear',
        	'data'=> array(),
                'attributes="margin: 10px;"',
     	)/*,
     	array(
     		'partialModuleName'=>'dmUser',
     		'partialName'=>'glist',
        	'data'=> $this->getPropertiesGenericListData($client),
     	) */
     );


  }


}
