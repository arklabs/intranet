<?php
/**
 * Feed Back components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class eventFeedBackComponents extends myFrontModuleComponents
{

  public function executeFbHistoryDatesRangeSelector()
  {
    $this->dateEnd = new sfDate(time());
     $this->dateStart = $this->dateEnd->copy();
     $this->dateEnd = $this->dateEnd->dump();
     $this->dateStart->subtractWeek(2);
     $this->dateStart = $this->dateStart->dump();
  }

  public function executeList()
  {
   try{

      	$date_end = $this->getRequestParameter('date_end', '');
      	$date_start = $this->getRequestParameter('date_start', '');

        $userId = ($this->getUser()->getGuardUser())?$this->getUser()->getGuardUser()->getId():-1;
        $q = null;
        Doctrine::getTable('Event')->forUser($this->getUser()->getGuardUser(), $q);

      	if ($date_start != ''){
            $date_start = new sfDate($date_start);
            Doctrine::getTable('Event')->starting($date_start->dump(), $q);
      	}
      	if ($date_end != ''){
          $date_end = new sfDate($date_end);
          Doctrine::getTable('Event')->ending($date_end->addDay(1)->dump(), $q);
      	}

        $this->eventFeedBackPager = $this->getPager($q);
      	}catch(Exception $e){ echo $e->getMessage(); return false;}

  }


}
