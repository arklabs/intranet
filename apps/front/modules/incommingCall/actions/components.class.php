<?php
/**
 * Llamadas entrantes components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 * 
 * 
 * 
 * 
 */
class incommingCallComponents extends myFrontModuleComponents
{

  public function executeClientList()
  {
    $this->clientPager = $this->getPager(Doctrine::getTable('Client')->createQuery());
  }

  public function executeExternalAgentList()
  {
    $this->externalAgentPager = $this->getPager(Doctrine::getTable('ExternalAgent')->createQuery());
  }

  public function executeAssessorList()
  {
    // $this->externalAgentPager = $this->getPager(Doctrine::getTable('ExternalAgent')->createQuery(););
  }

  public function executeAffiliateList()
  {
    $this->affiliatePager = $this->getPager(Doctrine::getTable('Affiliate')->createQuery());
  }

  public function executeAgentList()
  {
    $this->affiliatePager = $this->getPager(Doctrine::getTable('Affiliate')->createQuery());
  }

  public function executeInternalAgentList()
  {
    // Your code here
  }

  public function executeList()
  {
    try{
      	$date_end = $this->getRequestParameter('date_end', '');
      	$date_start = $this->getRequestParameter('date_start', '');
      	$table = Doctrine::getTable('IncommingCall');
      	$query = null;;
      	if ($date_start != ''){
    $date_start = new sfDate($date_start);
    		$table->starting($date_start->dump(), $query);  		
      	}
      	if ($date_end != ''){
      $date_end = new sfDate($date_end);
      $table->ending($date_end->addDay(1)->dump(), $query);
      	}
      	if ($query == null) $query = Doctrine::getTable('IncommingCall')->createQuery();
    $this->incommingCallPager = $this->getPager($query);
      	}catch(Exception $e){ echo $e->getMessage(); return false;}
  }

  public function executeHistoryDatesRangeSelector()
  {
    $this->dateEnd = new sfDate(time());
     $this->dateStart = $this->dateEnd->copy();
     $this->dateEnd = $this->dateEnd->dump();
     $this->dateStart->subtractWeek(2);
     $this->dateStart = $this->dateStart->dump();
  }

  public function executeCallsPerDayList()
  {
        

        // initializing series first
        $totalCallsPerDay = array();
        $clientCallsPerDay = array();
        $agentCallsPerDay = array();
        $affliateCallsPerDay = array();
        $externalAgent = array();
        $selectedDaysList = array();
        
        $startingDate = $this->getRequest()->getParameter('dateStart');
        $endingDate = $this->getRequest()->getParameter('dateEnd');

        $startDate = new sfDate($startingDate);
        $endDate = new sfDate($endingDate);

        $endingDate = $endDate->copy()->addDay(1)->dump();  // parche para que grafique tambien hasta lo que va de dia cuando es por defecto

        $startDate->clearTime(); $endDate->clearTime();

        $cursorDate = $endDate->copy();
        while ($cursorDate->get()>= $startDate->get()){
            $totalCallsPerDay[$cursorDate->get()] = 0;
            $clientCallsPerDay[$cursorDate->get()] = 0;
            $agentCallsPerDay[$cursorDate->get()] = 0;
            $affliateCallsPerDay[$cursorDate->get()] = 0;
            $externalAgentCallsPerDay[$cursorDate->get()] = 0;
            array_push($selectedDaysList,$cursorDate->get());

            $cursorDate->subtractDay(1);
        }
      
        
        $q = null;
        Doctrine::getTable('IncommingCall')->starting($startingDate, $q)->ending($endingDate, $q);

        $calls = $q->execute();

        foreach ($calls as $c){
            $date = new sfDate($c->getCreatedAt());

            $date->clearTime();
            $totalCallsPerDay[$date->get()]++;
            switch ($c->getDmUser()->getType()){
                case 'Client':
                    $clientCallsPerDay[$date->get()]++;
                    break;
                case 'Affiliate':
                    $affliateCallsPerDay[$date->get()]++;
                    break;
                case 'ExternalAgent':
                    $externalAgentCallsPerDay[$date->get()]++;
                case 'Agent':
                    $agentCallsPerDay[$date->get()]++;
                    break;
            }
        }
        
        // finally filling list array data
         $this->showColumns = array(
          'Fecha'=>array('label'=>'Fecha', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'date'),
          'Total'=>array('label'=>'Total', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Clientes'=>array('label'=>'Clientes', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Agentes'=>array('label'=>'Agentes', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Agentes Externos'=>array('label'=>'Agentes Externos', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Afiliados'=>array('label'=>'Afiliados', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
         );
          $this->listArray = array();
          foreach($selectedDaysList as $day)
          {
              array_push($this->listArray,  array(
              'Fecha'=> date('Y-m-d',$day),
              'Clientes'=>$clientCallsPerDay[$day],
              'Agentes'=>$agentCallsPerDay[$day],
              'Agentes Externos'=>$externalAgentCallsPerDay[$day],
              'Afiliados'=>$affliateCallsPerDay[$day],
              'Total'=> $totalCallsPerDay[$day]
            ));
          }
  }

  public function executeCallsPerDayGraph()
  {
    	$startingDate = $this->getRequest()->getParameter('dateStart').' 00:00:00';
        $endingDate = $this->getRequest()->getParameter('dateEnd').' 00:00:00';

        
        // initializing series first
        $totalCallsPerDay = array();
        $clientCallsPerDay = array();
        $agentCallsPerDay = array();
        $affliateCallsPerDay = array();
        $externalAgent = array();
        $selectedDaysList = array();
        
        $startDate = new sfDate($startingDate);
        $endDate = new sfDate($endingDate);

        $endingDate = $endDate->copy()->addDay(1)->dump();  // parche para que grafique tambien hasta lo que va de dia cuando es por defecto

        $startDate->clearTime(); $endDate->clearTime();

        $cursorDate = $startDate->copy();
        while ($cursorDate->get()<= $endDate->get()){
            $totalCallsPerDay[$cursorDate->get()] = 0;
            $clientCallsPerDay[$cursorDate->get()] = 0;
            $agentCallsPerDay[$cursorDate->get()] = 0;
            $affliateCallsPerDay[$cursorDate->get()] = 0;
            $externalAgentCallsPerDay[$cursorDate->get()] = 0;
            array_push($selectedDaysList,$cursorDate->getDay());

            $cursorDate->addDay(1);
        }

        // getting data
        $q = null;
        Doctrine::getTable('IncommingCall')->starting($startingDate, $q)->ending($endingDate, $q);
        
        $calls = $q->execute();
        
        foreach ($calls as $c){
            $date = new sfDate($c->getCreatedAt());
            
            $date->clearTime();
            $totalCallsPerDay[$date->get()]++;
            switch ($c->getDmUser()->getType()){
                case 'Client':
                    $clientCallsPerDay[$date->get()]++;
                    break;
                case 'Affiliate':
                    $affliateCallsPerDay[$date->get()]++;
                    break;
                case 'ExternalAgent':
                    $externalAgentCallsPerDay[$date->get()]++;
                case 'Agent':
                    $agentCallsPerDay[$date->get()]++;
                    break;
            }
        }
    	$arkChart = new arkPChart('Llamadas Diarias', 'perDayIncommingCallChart');
        
    	$arkChart->AddSerie('Clientes', array_values($clientCallsPerDay));
    	$arkChart->AddSerie('Agentes', array_values($agentCallsPerDay));
        $arkChart->AddSerie('Agentes Externos', array_values($externalAgentCallsPerDay));
        $arkChart->AddSerie('Afiliados', array_values($affliateCallsPerDay));
        $arkChart->AddSerie('Dias', $selectedDaysList, true);
        $arkChart->AddSerie('Total de llamadas', array_values($totalCallsPerDay));

	$arkChart->draw();
    	// adding % labels
    	/*for ($index = 0; $index < count($SerieX); $index++) {
            $arkChart->addLabel('Citas Finalizadas', $SerieX[$index],$SeriePorciento[$index]);
	}*/
	
        echo $arkChart->render(); 
	return true;
	
  }

  public function executeReportCallsPerMonthConfig()
  {
    // Your code here
  }

  public function executeReportCallsPerDayConfig()
  {
    // Your code here
  }

  public function executeReportCallsPerReasonConfig()
  {
    // Your code here
  }

  public function executeCallsPerMonthGraph()
  {
    	$startingDate = $this->getRequest()->getParameter('dateStart').' 00:00:00';
        $endingDate = $this->getRequest()->getParameter('dateEnd').' 00:00:00';


        // initializing series first
        $totalCallsPerDay = array();
        $clientCallsPerDay = array();
        $agentCallsPerDay = array();
        $affliateCallsPerDay = array();
        $externalAgent = array();
        $selectedDaysList = array();

        $startDate = new sfDate($startingDate);
        $endDate = new sfDate($endingDate);

        $endingDate = $endDate->copy()->addDay(1)->dump();  // parche para que grafique tambien hasta lo que va de dia cuando es por defecto

        $startDate->firstDayOfMonth()->clearTime(); $endDate->finalDayOfMonth()->clearTime();

        $cursorDate = $startDate->copy();
        while ($cursorDate->get()<= $endDate->get()){
            $totalCallsPerDay[$cursorDate->get()] = 0;
            $clientCallsPerDay[$cursorDate->get()] = 0;
            $agentCallsPerDay[$cursorDate->get()] = 0;
            $affliateCallsPerDay[$cursorDate->get()] = 0;
            $externalAgentCallsPerDay[$cursorDate->get()] = 0;
            array_push($selectedDaysList,date('M', $cursorDate->get()));

            $cursorDate->addMonth(1);
        }

        // getting data
        $q = null;
        Doctrine::getTable('IncommingCall')->starting($startingDate, $q)->ending($endingDate, $q);

        $calls = $q->execute();

        foreach ($calls as $c){
            $date = new sfDate($c->getCreatedAt());

            $date->firstDayOfMonth()->clearTime();
            $totalCallsPerDay[$date->get()]++;
            switch ($c->getDmUser()->getType()){
                case 'Client':
                    $clientCallsPerDay[$date->get()]++;
                    break;
                case 'Affiliate':
                    $affliateCallsPerDay[$date->get()]++;
                    break;
                case 'ExternalAgent':
                    $externalAgentCallsPerDay[$date->get()]++;
                case 'Agent':
                    $agentCallsPerDay[$date->get()]++;
                    break;
            }
        }
    	$arkChart = new arkPChart('Llamadas Mensuales', 'perMonthIncommingCallChart');

    	$arkChart->AddSerie('Clientes', array_values($clientCallsPerDay));
    	$arkChart->AddSerie('Agentes', array_values($agentCallsPerDay));
        $arkChart->AddSerie('Agentes Externos', array_values($externalAgentCallsPerDay));
        $arkChart->AddSerie('Afiliados', array_values($affliateCallsPerDay));
        $arkChart->AddSerie('Dias', $selectedDaysList, true);
        $arkChart->AddSerie('Total de llamadas', array_values($totalCallsPerDay));

	$arkChart->draw();
    	// adding % labels
    	/*for ($index = 0; $index < count($SerieX); $index++) {
            $arkChart->addLabel('Citas Finalizadas', $SerieX[$index],$SeriePorciento[$index]);
	}*/

        echo $arkChart->render();
	return true;
  }

  public function executeCallsPerMonthList()
  {
    // initializing series first
        $totalCallsPerMonth= array();
        $clientCallsPerMonth = array();
        $agentCallsPerMonth = array();
        $affliateCallsPerMonth = array();
        $externalAgent = array();
        $selectedDaysList = array();

        $startingDate = $this->getRequest()->getParameter('dateStart');
        $endingDate = $this->getRequest()->getParameter('dateEnd');

        $startDate = new sfDate($startingDate);
        $endDate = new sfDate($endingDate);

        $startDate->firstDayOfMonth()->clearTime(); $endDate->finalDayOfMonth()->addDay(1)->clearTime();

	$startingDate = $startDate->dump(); $endingDate = $endDate->dump();

        $cursorDate = $endDate->copy();
        while ($cursorDate->get()> $startDate->get()){
            $cursorDate->firstDayOfMonth()->subtractMonth(1);
            $totalCallsPerMonth[$cursorDate->get()] = 0;
            $clientCallsPerMonth[$cursorDate->get()] = 0;
            $agentCallsPerMonth[$cursorDate->get()] = 0;
            $affliateCallsPerMonth[$cursorDate->get()] = 0;
            $externalAgentCallsPerMonth[$cursorDate->get()] = 0;
            array_push($selectedDaysList,$cursorDate->get());

           
        }


        $q = null;
        Doctrine::getTable('IncommingCall')->starting($startingDate, $q)->ending($endingDate, $q);

        $calls = $q->execute();

        foreach ($calls as $c){
            $date = new sfDate($c->getCreatedAt());

            $date->firstDayOfMonth()->clearTime();
            $totalCallsPerMonth[$date->get()]++;
            switch ($c->getDmUser()->getType()){
                case 'Client':
                    $clientCallsPerMonth[$date->get()]++;
                    break;
                case 'Affiliate':
                    $affliateCallsPerMonth[$date->get()]++;
                    break;
                case 'ExternalAgent':
                    $externalAgentCallsPerMonth[$date->get()]++;
                case 'Agent':
                    $agentCallsPerMonth[$date->get()]++;
                    break;
            }
        }

        // finally filling list array data
         $this->showColumns = array(
          'Fecha'=>array('label'=>'Mes', 'href'=>'','extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Clientes'=>array('label'=>'Clientes', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Agentes'=>array('label'=>'Agentes', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Agentes Externos'=>array('label'=>'Agentes Externos', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Afiliados'=>array('label'=>'Afiliados', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
		  'Total'=>array('label'=>'Total', 'href'=>'', 'is_relation'=>0, 'type'=>'string')
         );
          $this->listArray = array();

          foreach($selectedDaysList as $day)
          {
              array_push($this->listArray,  array(
              'Fecha'=> date('M',$day),
              'Clientes'=>$clientCallsPerMonth[$day],
              'Agentes'=>$agentCallsPerMonth[$day],
              'Agentes Externos'=>$externalAgentCallsPerMonth[$day],
              'Afiliados'=>$affliateCallsPerMonth[$day],
              'Total'=> $totalCallsPerMonth[$day]
            ));
          }
  }

  public function executeCallsPerReasonGraph()
  {
     $startingDate = $this->getRequest()->getParameter('dateStart').' 00:00:00';
        $endingDate = $this->getRequest()->getParameter('dateEnd').' 00:00:00';


        // initializing series first
        $totalCallsPerReason = array();
        $foundReasons = array();

        $startDate = new sfDate($startingDate);
        $endDate = new sfDate($endingDate);

        $endingDate = $endDate->copy()->addDay(1)->dump();  // parche para que grafique tambien hasta lo que va de dia cuando es por defecto

        
        // getting data
        $q = null;
        Doctrine::getTable('IncommingCall')->groupByReason($q)->starting($startingDate, $q)->ending($endingDate, $q);

        $calls = $q->execute();
        
        foreach ($calls as $c){
            array_push($totalCallsPerReason, $c->getRcallCount());
            array_push($foundReasons, $c->getIncommingCallReason()->getId());
        }
    	$arkChart = new arkPChart('Llamadas por razon', 'perReasonIncommingCallChart');

    	$arkChart->AddSerie('Llamadas', $totalCallsPerReason);
    	$arkChart->AddSerie('Razones', $foundReasons, true);

	$arkChart->draw();
    	

        echo $arkChart->render();
	return true;
  }

  public function executeCallsPerReasonList()
  {
       $startingDate = $this->getRequest()->getParameter('dateStart').' 00:00:00';
        $endingDate = $this->getRequest()->getParameter('dateEnd').' 00:00:00';


        // initializing series first
        $totalCallsPerReason = array();
        $foundReasons = array();

        $startDate = new sfDate($startingDate);
        $endDate = new sfDate($endingDate);

        $endingDate = $endDate->copy()->addDay(1)->dump();  // parche para que grafique tambien hasta lo que va de dia cuando es por defecto


        // getting data
        $q = null;
        Doctrine::getTable('IncommingCall')->groupByReason($q)->starting($startingDate, $q)->ending($endingDate, $q);

        $calls = $q->execute();

        foreach ($calls as $c){
            array_push($totalCallsPerReason, $c->getRcallCount());
            array_push($foundReasons, $c->getIncommingCallReason()->getId());
        }

        // finally filling list array data
         $this->showColumns = array(
          'code'=>array('label'=>'C&oacute;digo', 'href'=>'','extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'reason'=>array('label'=>'Raz&oacute;n de llamada', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'total'=>array('label'=>'Total', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string')
         );
          $this->listArray = array();

          foreach($calls as $c)
          {
              array_push($this->listArray,  array(
              'code'=> $c->getIncommingCallReason()->getId(),
              'reason'=>$c->getIncommingCallReason()->getName(),
              'total'=> $c->getRcallCount(),
            ));
          }
	
  }


}
