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
      Doctrine::getTable('Event')->forUser($userId, $q)->nonPending($q);
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
      Doctrine::getTable('Event')->forUser($userId, $q)->filterByCategoryName('cita', $q);
      $q = $q->leftJoin('e.CreatedBy createdBy')->limit(200);
      
      $this->eventPager = $this->getPager($q);
  }

  public function executePerDayDateChart()
  { 
        $startingDate = $this->getRequest()->getParameter('dateStart').' 00:00:00';
        $endingDate = $this->getRequest()->getParameter('dateEnd').' 00:00:00';
    	$arkChart = new arkPChart('Citas Asignadas y Finalizadas por Agente', 'perDayDateChart');

        $agents = Doctrine::getTable('Agent')->createQuery()->execute();
     	$Serie1 = array();
    	$Serie2 = array();
    	$SeriePorciento = array();
    	$SerieX = array();
    	foreach($agents as $agent)
      	{
          $dates = $agent->getActiveEventsByCategoryQuery(1, null, $startingDate, $endingDate);
          $agentDates = $dates->count();
          $agentDatesFinished = $agent->countActiveEventsByStatus(2,$dates, null, null);
          array_push($Serie1, $agentDates);
          array_push($Serie2, $agentDatesFinished);
          array_push($SerieX, $agent->__toString().' ');
          array_push($SeriePorciento, round((($agentDatesFinished*100)/max($agentDates,1)),1).'%');
      	}

    	$arkChart->AddSerie('Citas Asignadas', $Serie1);
    	$arkChart->AddSerie('Citas Finalizadas', $Serie2);
    	$arkChart->AddSerie('Agentes', $SerieX, true); // true al final si es la serie de las x
	$arkChart->draw();
    	// adding % labels
    	for ($index = 0; $index < count($SerieX); $index++) {
            $arkChart->addLabel('Citas Finalizadas', $SerieX[$index],$SeriePorciento[$index]);
	}

        echo $arkChart->render();
	return true;
  }
  
  public function executePerMonthDateReport()
  {
    $this->dates = Doctrine::getTable('Event')->getEventsByCategoryAssignedTo(1);
  }

  public function executePerMonthDateReportPdf()
  {
    // Your code here
  }
  public function executeAgentAssignDatesWithRangeSelector(){
  	 $this->dateEnd = new sfDate(time());
     $this->dateStart = $this->dateEnd->copy();
     $this->dateEnd = $this->dateEnd->dump();
     $this->dateStart->subtractWeek(2);
     $this->dateStart = $this->dateStart->dump();
  }
  public function executeAgentAssignDates(){
  	  $endDate = new sfDate($this->getRequestParameter('date_end', time()));
  	  $startDate = new sfDate($this->getRequestParameter('date_start', $endDate->copy()->subtractWeek(2)->get()));

  	  $userId = ($this->getUser()->getGuardUser())?$this->getUser()->getGuardUser()->getId():-1;
      $q = null;
      Doctrine::getTable('Event')->starting($startDate->dump(), $q)->ending($endDate->dump(), $q);

      $this->agentList = Doctrine::getTable('Agent')->createQuery()->execute();
      
      $this->eventPager = $this->getPager($q);
  }
}
 
