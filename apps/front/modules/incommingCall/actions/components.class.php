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
  public static $_ParsedDashYamlFile = null;

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
        $prospectCallsPerDay = array();
        $activeModsCallsPerDay = array();
        $realestateCallsPerDay = array();
        $inventaryCallsPerDay = array();
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
            $prospectCallsPerDay[$cursorDate->get()] = 0;
            $activeModsCallsPerDay[$cursorDate->get()] = 0;
            $realestateCallsPerDay[$cursorDate->get()] = 0;
            $inventaryCallsPerDay[$cursorDate->get()] = 0;
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
            switch ($c->getType()){
                case 'IncommingCallProspect':
                    $prospectCallsPerDay[$date->get()]++;
                    break;
                case 'IncommingCallRealState':
                    $realestateCallsPerDay[$date->get()]++;
                    break;
                case 'IncommingCallInventrary':
                    $inventaryCallsPerDay[$date->get()]++;
                case 'IncommingCallActiveMod':
                    $activeModsCallsPerDay[$date->get()]++;
                    break;
            }
        }
        
        // finally filling list array data
         $this->showColumns = array(
          'Fecha'=>array('label'=>'Fecha', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'date'),
          'Prospectos'=>array('label'=>'Prospectos', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Active Mods'=>array('label'=>'Active Mods', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Inventario'=>array('label'=>'Agentes Externos', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Real Estate'=>array('label'=>'Afiliados', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Total'=>array('label'=>'Total', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
         );
          $this->listArray = array();
          foreach($selectedDaysList as $day)
          {
              array_push($this->listArray,  array(
              'Fecha'=> date('Y-m-d',$day),
              'Prospectos'=>$prospectCallsPerDay[$day],
              'Active Mods'=>$activeModsCallsPerDay[$day],
              'Inventario'=>$inventaryCallsPerDay[$day],
              'Real Estate'=>$realestateCallsPerDay[$day],
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
        $prospectCallsPerDay = array();
        $activeModsCallsPerDay = array();
        $realestateCallsPerDay = array();
        $inventaryCallsPerDay = array();
        $selectedDaysList = array();
        
        $startDate = new sfDate($startingDate);
        $endDate = new sfDate($endingDate);

        $endingDate = $endDate->copy()->addDay(1)->dump();  // parche para que grafique tambien hasta lo que va de dia cuando es por defecto

        $startDate->clearTime(); $endDate->clearTime();

        $cursorDate = $startDate->copy();
        while ($cursorDate->get()<= $endDate->get()){
            $totalCallsPerDay[$cursorDate->get()] = 0;
            $prospectCallsPerDay[$cursorDate->get()] = 0;
            $activeModsCallsPerDay[$cursorDate->get()] = 0;
            $realestateCallsPerDay[$cursorDate->get()] = 0;
            $inventaryCallsPerDay[$cursorDate->get()] = 0;
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
            switch ($c->getType()){
                case 'IncommingCallProspect':
                    $prospectCallsPerDay[$date->get()]++;
                    break;
                case 'IncommingCallRealState':
                    $realestateCallsPerDay[$date->get()]++;
                    break;
                case 'IncommingCallInventrary':
                    $inventaryCallsPerDay[$date->get()]++;
                case 'IncommingCallActiveMod':
                    $activeModsCallsPerDay[$date->get()]++;
                    break;
            }
        }
    	$arkChart = new arkPChart('Llamadas Diarias', 'perDayIncommingCallChart');
        
    	$arkChart->AddSerie('Prospectos', array_values($prospectCallsPerDay));
    	$arkChart->AddSerie('Active Mods', array_values($activeModsCallsPerDay));
        $arkChart->AddSerie('Inventario', array_values($inventaryCallsPerDay));
        $arkChart->AddSerie('Real Estate', array_values($realestateCallsPerDay));
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
        $prospectCallsPerMonth = array();
        $activeModsCallsPerMonth = array();
        $realestateCallsPerMonth = array();
        $inventaryCallsPerMonth= array();
        $selectedDaysList = array();

        $startDate = new sfDate($startingDate);
        $endDate = new sfDate($endingDate);

        $endingDate = $endDate->copy()->addDay(1)->dump();  // parche para que grafique tambien hasta lo que va de dia cuando es por defecto

        $startDate->firstDayOfMonth()->clearTime(); $endDate->finalDayOfMonth()->clearTime();

        $cursorDate = $startDate->copy();
        while ($cursorDate->get()<= $endDate->get()){
            $totalCallsPerDay[$cursorDate->get()] = 0;
            $prospectCallsPerMonth[$cursorDate->get()] = 0;
            $activeModsCallsPerMonth[$cursorDate->get()] = 0;
            $realestateCallsPerMonth[$cursorDate->get()] = 0;
            $inventaryCallsPerMonth[$cursorDate->get()] = 0;
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
            switch ($c->getType()){
                case 'IncommingCallProspect':
                    $prospectCallsPerMonth[$date->get()]++;
                    break;
                case 'IncommingCallRealState':
                    $realestateCallsPerMonth[$date->get()]++;
                    break;
                case 'IncommingCallInventrary':
                    $inventaryCallsPerMonth[$date->get()]++;
                case 'IncommingCallActiveMod':
                    $activeModsCallsPerMonth[$date->get()]++;
                    break;
            }
        }
    	$arkChart = new arkPChart('Llamadas Mensuales', 'perMonthIncommingCallChart');

    	$arkChart->AddSerie('Prospectos', array_values($prospectCallsPerMonth));
    	$arkChart->AddSerie('Active Mods', array_values($activeModsCallsPerMonth));
        $arkChart->AddSerie('Inventario', array_values($inventaryCallsPerMonth));
        $arkChart->AddSerie('Real Estate', array_values($realestateCallsPerMonth));
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
        $prospectCallsPerMonth = array();
        $activeModsCallsPerMonth = array();
        $realestateCallsPerMonth = array();
        $inventaryCallsPerDay = array();
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
            $prospectCallsPerMonth[$cursorDate->get()] = 0;
            $activeModsCallsPerMonth[$cursorDate->get()] = 0;
            $realestateCallsPerMonth[$cursorDate->get()] = 0;
            $inventaryCallsPerMonth[$cursorDate->get()] = 0;
            array_push($selectedDaysList,$cursorDate->get());

           
        }


        $q = null;
        Doctrine::getTable('IncommingCall')->starting($startingDate, $q)->ending($endingDate, $q);

        $calls = $q->execute();

        foreach ($calls as $c){
            $date = new sfDate($c->getCreatedAt());

            $date->firstDayOfMonth()->clearTime();
            $totalCallsPerMonth[$date->get()]++;
            switch ($c->getType()){
                case 'IncommingCallProspect':
                    $prospectCallsPerMonth[$date->get()]++;
                    break;
                case 'IncommingCallRealState':
                    $realestateCallsPerMonth[$date->get()]++;
                    break;
                case 'IncommingCallInventrary':
                    $inventaryCallsPerMonth[$date->get()]++;
                case 'IncommingCallActiveMod':
                    $activeModsCallsPerMonth[$date->get()]++;
                    break;
            }
        }

        // finally filling list array data
         $this->showColumns = array(
          'Fecha'=>array('label'=>'Mes', 'href'=>'','extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Prospectos'=>array('label'=>'Prospectos', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Active Mods'=>array('label'=>'Active Mods', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Inventario'=>array('label'=>'Inventario', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'Real Estate'=>array('label'=>'Real Estate', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
	  'Total'=>array('label'=>'Total', 'href'=>'', 'is_relation'=>0, 'type'=>'string')
         );
          $this->listArray = array();

          foreach($selectedDaysList as $day)
          {
              array_push($this->listArray,  array(
              'Fecha'=> date('M',$day),
              'Prospectos'=>$prospectCallsPerMonth[$day],
              'Active Mods'=>$activeModsCallsPerMonth[$day],
              'Inventario'=>$inventaryCallsPerMonth[$day],
              'Real Estate'=>$realestateCallsPerMonth[$day],
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
        Doctrine::getTable('IncommingCall')->starting($startingDate, $q)->ending($endingDate, $q);
        $calls = $q->execute();
        foreach ($calls as $c){
            if ($c->getReasonId()){
                if (!array_key_exists($c->getReasonId(), $foundReasons)){
                    $foundReasons[$c->getReasonId()] = 0;
                }
                $foundReasons[$c->getReasonId()]++;
            }
        }
        
    	$arkChart = new arkPChart('Llamadas por razon', 'perReasonIncommingCallChart');

    	$arkChart->AddSerie('Razones', array_keys($foundReasons), true);
    	$arkChart->AddSerie('Llamadas', array_values($foundReasons));

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
        Doctrine::getTable('IncommingCall')->starting($startingDate, $q)->ending($endingDate, $q);
        $calls = $q->execute();
        foreach ($calls as $c){
            if ($c->getReasonId()){
                if (!array_key_exists($c->getReasonId(), $foundReasons)){
                    $foundReasons[$c->getReasonId()] = 0;
                }
                $foundReasons[$c->getReasonId()]++;
            }
        }

        // finally filling list array data
         $this->showColumns = array(
          'code'=>array('label'=>'C&oacute;digo', 'href'=>'','extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'reason'=>array('label'=>'Raz&oacute;n de llamada', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'total'=>array('label'=>'Total', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string')
         );
          $this->listArray = array();

          foreach(array_keys($foundReasons) as $rk)
          {
              $reason = Doctrine::getTable('IncommingCallReason')->findById($rk);
              if (count($reason) > 0){
                  array_push($this->listArray,  array(
                  'code'=> $rk,
                  'reason'=>$reason[0]->getName(),
                  'total'=> $foundReasons[$rk]
                ));
              }
          }
	
  }

  public function executeIncommingCallDashboard()
  {
    if (is_null(self::$_ParsedDashYamlFile)){
          // parse sidebar configuration yml file.
          $configFile = sfConfig::get('sf_app_dir') . '/config/qdashboard.yml';
            if (!file_exists($configFile)) {
                return;
            }
            self::$_ParsedDashYamlFile = sfYaml::load($configFile);
      }
      $config = self::$_ParsedDashYamlFile;
      $this->dashDescription = $config['dashboards'];
      $this->dashDescription = $this->dashDescription['incomming-calls'];
      if (is_null($this->requestedDashBoardName)||$this->requestedDashBoardName==''){
            return ''; // dashboard name most be passed
      }
  }
  


}
