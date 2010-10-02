<?php
/**
 * Evento components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */
class eventComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['Event'];
  }

  public function executeList()
  {	
	  $this->availableStatus = Doctrine::getTable('EventStatus')->getAll();
      $userId = ($this->getUser()->getGuardUser())?$this->getUser()->getGuardUser()->getId():-1;
      $q = null;
      Doctrine::getTable('Event')->forUser($this->getUser()->getGuardUser(), $q);
      $q = $q->leftJoin('e.CreatedBy createdBy')->limit(200);
      $this->eventPager = $this->getPager($q);
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    $this->event = $this->getRecord($query);
  }

  public function executeFullcalendar()
  {
    // Your code here
  }

  public function executeGeoEventsList()
  {
    $this->availableStatus = Doctrine::getTable('EventStatus')->getAll();
      $userId = ($this->getUser()->getGuardUser())?$this->getUser()->getGuardUser()->getId():-1;
      $q = null;
      Doctrine::getTable('Event')->forUser($this->getUser()->getGuardUser(), $q)->filterByCategoryName('cita', $q);
      $q = $q->leftJoin('e.CreatedBy createdBy')->limit(200);
      
      $this->eventPager = $this->getPager($q);
  }

  public function executePerMonthDateReport()
  {
    $this->dates = Doctrine::getTable('Event')->getEventsByCategoryAssignedTo(1);
  }

  public function executePerMonthDateReportPdf()
  {
    // Your code here
  }

  public function executeAgentAssignDatesWithRangeSelector()
  {
    $this->dateEnd = new sfDate(time());
     $this->dateStart = $this->dateEnd->copy();
     $this->dateEnd = $this->dateEnd->dump();
     $this->dateStart->subtractWeek(2);
     $this->dateStart = $this->dateStart->dump();
  }

  public function executeAgentAssignDates()
  {
      
     $endDate = new sfDate($this->getRequestParameter('date_end', time()));
     $endDate->addDay(1)->clearTime();
     $startDate = new sfDate($this->getRequestParameter('date_start', $endDate->copy()->subtractWeek(2)->get()));
     $userId = ($this->getUser()->getGuardUser())?$this->getUser()->getGuardUser()->getId():-1;
      $q = null;
      
      Doctrine::getTable('Event')->reportStarting($startDate->dump(), $q)->reportEnding($endDate->dump(), $q);
      
      $this->agentList = Doctrine::getTable('Agent')->createQuery()->execute();
      
      $this->eventPager = $this->getPager($q);
  }

  public function executeReportDatesByCityConfig()
  {
    // Your code here
  }

  public function executeReportDatesByAgentConfig()
  {
    // Your code here
  }

  public function executeReportDatesByTelemarkerConfig()
  {
    // Your code here
  }

  public function executePerAgentDateChart()
  {
        $startingDate = $this->getRequest()->getParameter('dateStart').' 00:00:00';
        $endingDate = $this->getRequest()->getParameter('dateEnd').' 00:00:00';
		$endingDate = new sfDate($endingDate); $endingDate = $endingDate->addDay(1)->dump();
    	$arkChart = new arkPChart('Reporte General de Citas por Agente', 'perDayAgentDateChart');
        $q = null;
        Doctrine::getTable('Event')->reportStarting($startingDate, $q)->reportEnding($endingDate, $q);
        $events = $q->execute();
        $agents = array();
        foreach ($events as $e){
            $user = $e->getDmUser();
            if (count($user) && $user[0]->hasGroup('agente')){
                if (!array_key_exists($e->getDmUserId(), $agents)){
                    $agents[$e->getDmUserId()] = array();
                }
                if (!array_key_exists($e->getEventStatus()->getName(), $agents[$e->getDmUser()]))
                    $agents[$e->getDmUserId()][$e->getEventStatus()->getName()] = 0;
                $agents[$e->getDmUserId()][$e->getEventStatus()->getName()]++;
                $agents[$e->getDmUserId()]['Total']++;
            }
        }
        
        $eventStatus = Doctrine::getTable('EventStatus')->createQuery()->execute();
        $statusSeries = array();
        $totalSerie = array();
        $porCienSerie = array();
        foreach ($eventStatus as $s){
            if (!array_key_exists($s->getName(), $statusSeries)){
                $statusSeries[$s->getName()] = array();
            }
            foreach ($agents as $ag){
                array_push($statusSeries[$s->getName()], $ag[$s->getName()]);
                if ($s->getName() == 'Cerrado')
                   array_push($porCienSerie, round((($ag[$s->getName()]*100)/max($ag['Total'],1)),1).'%');
            }
            if (count($statusSeries[$s->getName()]))
                $arkChart->addSerie($s->getName(), $statusSeries[$s->getName()]);
        }
        $agentNames = array();
        foreach (array_keys($agents) as $agk){
            array_push($totalSerie, $ag[$agk]['Total']);
            
            $name = Doctrine::getTable('Agent')->findById($agk);
            if (count($name)>0)
                $name = $name[0]->getFirstName().' '.$name[0]->getLastName().' ';
            else
                $name = $agk;
            array_push($agentNames, $name);
        }
        if (count($totalSerie))
            $arkChart->addSerie('Total', $totalSerie);
        if (count($agentNames))
            $arkChart->addSerie('Agentes', $agentNames, true);
        /*for ($index = 0; $index < count($agentNames); $index++) {
            $percent = ($porCienSerie[$index])?$porCienSerie[$index]:'0%';
            $arkChart->addLabel('Agentes', $agentNames[$index], $percent);
    	} */
        $arkChart->draw();
        echo $arkChart->render();
    	return true;
  }

  public function executePerAgentDateList()
  {
    $startingDate = $this->getRequest()->getParameter('dateStart').' 00:00:00';
        $endingDate = $this->getRequest()->getParameter('dateEnd').' 00:00:00';
		$endingDate = new sfDate($endingDate); $endingDate = $endingDate->addDay(1)->dump();
        $q = null;
        Doctrine::getTable('Event')->reportStarting($startingDate, $q)->reportEnding($endingDate, $q);
        $events = $q->execute();
        $agents = array();
        foreach ($events as $e){
			$user = $e->getDmUser();
            if (count($user) && $user[0]->hasGroup('agente')){
                if (!array_key_exists($e->getDmUserId(), $agents)){
                    $agents[$e->getDmUserId()] = array();
                }
                if (!array_key_exists($e->getEventStatus()->getName(), $agents[$e->getDmUser()]))
                    $agents[$e->getDmUserId()][$e->getEventStatus()->getName()] = 0;
                $agents[$e->getDmUserId()][$e->getEventStatus()->getName()]++;
                $agents[$e->getDmUserId()]['Total']++;
            }
        }
         $agentNames = array();
        foreach (array_keys($agents) as $agk){
            array_push($totalSerie, $ag[$agk]['Total']);
            $name = Doctrine::getTable('Agent')->findById($agk);
            if (count($name)>0)
                $name = $name[0]->getFirstName().' '.$name[0]->getLastName().' ';
            else
                $name = $agk;
           $agentNames[$agk] =  $name;
        }
        
        $this->showColumns = array(
          'Nombre'=>array('label'=>'Nombre', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string')
         );
        $eventStatus = Doctrine::getTable('EventStatus')->createQuery()->execute();
        
        foreach ($eventStatus as $s){
            $this->showColumns[$s->getName()] = array('label'=>$s->getName(), 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string');
        }
        $this->showColumns['Total']= array('label'=>'Total', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string');
        
        // finally filling list array data
        $this->listArray = array();
        foreach (array_keys($agents) as $agk){
            $entryAr = array();
            $entryAr['Nombre'] = $agentNames[$agk];
            foreach ($eventStatus as $s){
                $entryAr[$s->getName()] = $agents[$agk][$s->getName()];
            }
            $entryAr['Total'] = $agents[$agk]['Total'];
            array_push($this->listArray, $entryAr);
        }
  }

  public function executePerTelemarketerDateChart()
  {
    $startingDate = $this->getRequest()->getParameter('dateStart').' 00:00:00';
        $endingDate = $this->getRequest()->getParameter('dateEnd').' 00:00:00';
		$endingDate = new sfDate($endingDate); $endingDate = $endingDate->addDay(1)->dump();
    	$arkChart = new arkPChart('Reporte General de Citas por Telemarcador', 'perTMDateChart');
        $q = null;
        Doctrine::getTable('Event')->reportStarting($startingDate, $q)->reportEnding($endingDate, $q);
        $events = $q->execute();
        $agents = array();
        foreach ($events as $e){
            if ($e->getCreatedBy() && $e->getCreatedBy()->hasGroup('telemarcador')){
                if (!array_key_exists($e->getCreatedBy()->getId(), $agents)){
                    $agents[$e->getCreatedBy()->getId()] = array();
                }
                if (!array_key_exists($e->getEventStatus()->getName(), $agents[$e->getCreatedBy()->getId()]))
                    $agents[$e->getCreatedBy()->getId()][$e->getEventStatus()->getName()] = 0;
                $agents[$e->getCreatedBy()->getId()][$e->getEventStatus()->getName()]++;
                $agents[$e->getCreatedBy()->getId()]['Total']++;
            }
        }
        
        $eventStatus = Doctrine::getTable('EventStatus')->createQuery()->execute();
        $statusSeries = array();
        $totalSerie = array();
        $porCienSerie = array();
        foreach ($eventStatus as $s){
            if (!array_key_exists($s->getName(), $statusSeries)){
                $statusSeries[$s->getName()] = array();
            }
            foreach ($agents as $ag){
                array_push($statusSeries[$s->getName()], $ag[$s->getName()]);
                if ($s->getName() == 'Cerrado')
                   array_push($porCienSerie, round((($ag[$s->getName()]*100)/max($ag['Total'],1)),1).'%');
            }
            if (count($statusSeries[$s->getName()])) 
                $arkChart->addSerie($s->getName(), $statusSeries[$s->getName()]);
        }
        $agentNames = array();
        foreach (array_keys($agents) as $agk){
            array_push($totalSerie, $ag[$agk]['Total']);
            $name = Doctrine::getTable('Telemarker')->findById($agk);
            if (count($name)>0)
                $name = $name[0]->getFirstName().' '.$name[0]->getLastName().' ';
            else
                $name = $agk;
            array_push($agentNames, $name);
        }
        if (count($totalSerie))
            $arkChart->addSerie('Total', $totalSerie);
        if (count($agentNames))
            $arkChart->addSerie('Telemarcador', $agentNames, true);
        /*for ($index = 0; $index < count($agentNames); $index++) {
            $percent = ($porCienSerie[$index])?$porCienSerie[$index]:'0%';
            $arkChart->addLabel('Telemarcador', $agentNames[$index], $percent);
    	}*/
        $arkChart->draw();
        echo $arkChart->render();
    	return true;
  }

  public function executePerTelemarketerDateList()
  {
    // Your code here
        $startingDate = $this->getRequest()->getParameter('dateStart').' 00:00:00';
        $endingDate = $this->getRequest()->getParameter('dateEnd').' 00:00:00';
		$endingDate = new sfDate($endingDate); $endingDate = $endingDate->addDay(1)->dump();
        $q = null;
        Doctrine::getTable('Event')->reportStarting($startingDate, $q)->reportEnding($endingDate, $q);
        $events = $q->execute();
        $agents = array();
        foreach ($events as $e){
            if ($e->getCreatedBy() && $e->getCreatedBy()->hasGroup('telemarcador')){
                if (!array_key_exists($e->getCreatedBy()->getId(), $agents)){
                    $agents[$e->getCreatedBy()->getId()] = array();
                }
                if (!array_key_exists($e->getEventStatus()->getName(), $agents[$e->getCreatedBy()->getId()]))
                    $agents[$e->getCreatedBy()->getId()][$e->getEventStatus()->getName()] = 0;
                $agents[$e->getCreatedBy()->getId()][$e->getEventStatus()->getName()]++;
                $agents[$e->getCreatedBy()->getId()]['Total']++;
            }
        }
         $agentNames = array();
        foreach (array_keys($agents) as $agk){
            array_push($totalSerie, $ag[$agk]['Total']);
            $name = Doctrine::getTable('Telemarker')->findById($agk);
            if (count($name)>0)
                $name = $name[0]->getFirstName().' '.$name[0]->getLastName().' ';
            else
                $name = $agk;
           $agentNames[$agk] =  $name;
        }
        $this->showColumns = array(
          'Nombre'=>array('label'=>'Nombre', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string')
         );
        $eventStatus = Doctrine::getTable('EventStatus')->createQuery()->execute();
        foreach ($eventStatus as $s){
            $this->showColumns[$s->getName()] = array('label'=>$s->getName(), 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string');
        }
        $this->showColumns['Total']= array('label'=>'Total', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string');
        // finally filling list array data
        $this->listArray = array();
        foreach (array_keys($agents) as $agk){
            $entryAr = array();
            $entryAr['Nombre'] = $agentNames[$agk];
            foreach ($eventStatus as $s){
                $entryAr[$s->getName()] = $agents[$agk][$s->getName()];
            }
            $entryAr['Total'] = $agents[$agk]['Total'];
            array_push($this->listArray, $entryAr);
        }
  }

  public function executePerMonthDateChart()
  {
        $startingDate = $this->getRequest()->getParameter('dateStart').' 00:00:00';
        $endingDate = $this->getRequest()->getParameter('dateEnd').' 00:00:00';
		$endingDate = new sfDate($endingDate); $endingDate = $endingDate->addDay(1)->dump();
        // initializing series first
        $totalDatesPerDay = array();
        $selectedDaysList = array();

        $startDate = new sfDate($startingDate);
        $endDate = new sfDate($endingDate);

        $endingDate = $endDate->copy()->addDay(1)->dump();  // parche para que grafique tambien hasta lo que va de dia cuando es por defecto

        $startDate->firstDayOfMonth()->clearTime(); $endDate->finalDayOfMonth()->clearTime();

        $cursorDate = $startDate->copy();
        while ($cursorDate->get()<= $endDate->get()){
            $totalDatesPerDay[$cursorDate->get()] = 0;
            array_push($selectedDaysList,date('M', $cursorDate->get()));

            $cursorDate->addMonth(1);
        }

        // getting data
        $q = null;
        Doctrine::getTable('Event')->starting($startingDate, $q)->reportEnding($endingDate, $q);

        $calls = $q->execute();

        foreach ($calls as $c){
            $date = new sfDate($c->getCreatedAt());

            $date->firstDayOfMonth()->clearTime();
            $totalDatesPerDay[$date->get()]++;
        }
    	$arkChart = new arkPChart('Citas Mensuales', 'perMonthDateChart');

        $arkChart->AddSerie('Meses', $selectedDaysList, true);
        $arkChart->AddSerie('Citas', array_values($totalDatesPerDay));

	$arkChart->draw();
    	// adding % labels
    	/*for ($index = 0; $index < count($SerieX); $index++) {
            $arkChart->addLabel('Citas Finalizadas', $SerieX[$index],$SeriePorciento[$index]);
	}*/

        echo $arkChart->render();
	return true;
  }

  public function executePerMonthDateList()
  {
        // initializing series first
        $totalDatesPerMonth= array();
        $selectedDaysList = array();

        $startingDate = $this->getRequest()->getParameter('dateStart');
        $endingDate = $this->getRequest()->getParameter('dateEnd');
		$endingDate = new sfDate($endingDate); $endingDate = $endingDate->addDay(1)->dump();

        $startDate = new sfDate($startingDate);
        $endDate = new sfDate($endingDate);

        $startDate->firstDayOfMonth()->clearTime(); $endDate->finalDayOfMonth()->addDay(1)->clearTime();

	$startingDate = $startDate->dump(); $endingDate = $endDate->dump();

        $cursorDate = $endDate->copy();
        while ($cursorDate->get()> $startDate->get()){
            $cursorDate->firstDayOfMonth()->subtractMonth(1);
            $totalDatesPerMonth[$cursorDate->get()] = 0;
            array_push($selectedDaysList,$cursorDate->get());
        }


        $q = null;
        Doctrine::getTable('Event')->reportStarting($startingDate, $q)->reportEnding($endingDate, $q);

        $calls = $q->execute();

        foreach ($calls as $c){
            $date = new sfDate($c->getCreatedAt());
            $date->firstDayOfMonth()->clearTime();
            $totalDatesPerMonth[$date->get()]++;
        }

        // finally filling list array data
         $this->showColumns = array(
          'Fecha'=>array('label'=>'Mes', 'href'=>'','extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
	  'Total'=>array('label'=>'Total', 'href'=>'', 'is_relation'=>0, 'type'=>'string')
         );
          $this->listArray = array();

          foreach($selectedDaysList as $day)
          {
              array_push($this->listArray,  array(
              'Fecha'=> date('M',$day),
              'Total'=> $totalDatesPerMonth[$day]
            ));
          }
  }

  public function executePerDayDateList()
  {
        // initializing series first
        $totalDatesPerDay = array();
        $selectedDaysList = array();

        $startingDate = $this->getRequest()->getParameter('dateStart');
        $endingDate = $this->getRequest()->getParameter('dateEnd');
		$endingDate = new sfDate($endingDate); $endingDate = $endingDate->addDay(1)->dump();

        $startDate = new sfDate($startingDate);
        $endDate = new sfDate($endingDate);

        $endingDate = $endDate->copy()->addDay(1)->dump();  // parche para que grafique tambien hasta lo que va de dia cuando es por defecto

        $startDate->clearTime(); $endDate->clearTime();

        $cursorDate = $endDate->copy();
        while ($cursorDate->get()>= $startDate->get()){
            $totalDatesPerDay[$cursorDate->get()] = 0;
            array_push($selectedDaysList,$cursorDate->get());

            $cursorDate->subtractDay(1);
        }


        $q = null;
        Doctrine::getTable('Event')->reportStarting($startingDate, $q)->reportEnding($endingDate, $q);

        $calls = $q->execute();

        foreach ($calls as $c){
            $date = new sfDate($c->getCreatedAt());

            $date->clearTime();
            $totalDatesPerDay[$date->get()]++;
        }

        // finally filling list array data
         $this->showColumns = array(
          'Fecha'=>array('label'=>'Fecha', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'date'),
          'Total'=>array('label'=>'Total', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
         );
          $this->listArray = array();
          foreach($selectedDaysList as $day)
          {
              array_push($this->listArray,  array(
              'Fecha'=> date('Y-m-d',$day),
              'Total'=> $totalDatesPerDay[$day]
            ));
          }
  }

  public function executePerDayDateChart()
  {
        $startingDate = $this->getRequest()->getParameter('dateStart').' 00:00:00';
        $endingDate = $this->getRequest()->getParameter('dateEnd').' 00:00:00';
		$endingDate = new sfDate($endingDate); $endingDate = $endingDate->addDay(1)->dump();


        // initializing series first
        $totalDatesPerDay = array();
        $selectedDaysList = array();

        $startDate = new sfDate($startingDate);
        $endDate = new sfDate($endingDate);

        $endingDate = $endDate->copy()->addDay(1)->dump();  // parche para que grafique tambien hasta lo que va de dia cuando es por defecto

        $startDate->clearTime(); $endDate->clearTime();

        $cursorDate = $startDate->copy();
        while ($cursorDate->get()<= $endDate->get()){
            $totalDatesPerDay[$cursorDate->get()] = 0;
            array_push($selectedDaysList,$cursorDate->getDay());

            $cursorDate->addDay(1);
        }

        // getting data
        $q = null;
        Doctrine::getTable('Event')->reportStarting($startingDate, $q)->reportEnding($endingDate, $q);

        $dates = $q->execute();

        foreach ($dates as $c){
            $date = new sfDate($c->getCreatedAt());

            $date->clearTime();
            $totalDatesPerDay[$date->get()]++;
        }
    	$arkChart = new arkPChart('Citas Diarias', 'perDayDateChart');

        $arkChart->AddSerie('Dias', $selectedDaysList, true);
        $arkChart->AddSerie('Total de Citas', array_values($totalDatesPerDay));

	$arkChart->draw();
    	// adding % labels
    	/*for ($index = 0; $index < count($SerieX); $index++) {
            $arkChart->addLabel('Citas Finalizadas', $SerieX[$index],$SeriePorciento[$index]);
	}*/

        echo $arkChart->render();
	return true;
  }

  public function executeReportDatesByDayConfig()
  {
    // Your code here
  }

  public function executeReportDatesByMonthConfig()
  {
    // Your code here
  }


}
