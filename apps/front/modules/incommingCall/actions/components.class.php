<?php
/**
 * Llamadas entrantes components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
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


}
