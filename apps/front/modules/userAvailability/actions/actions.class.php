<?php
/**
 * Disponibilidad actions
 */
class userAvailabilityActions extends myFrontModuleActions
{
    public function executeToogle(sfWebRequest $request){
        try{
        $day = $request->getParameter('id');
        $enable = $request->getParameter('value');
        if ($enable=="true") // add record to db
            UserAvailabilityTable::addAvailabilityRecord($this->getUser()->getDmUser()->getId(), $day);
        else
            UserAvailabilityTable::removeAvailabilityRecord($this->getUser()->getDmUser()->getId(), $day);
        return true;
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }
    }
    public function executeChangeAvailability(sfWebRequest $request){
        try{
        $months = $request->getParameter('meses');
        $days = $request->getParameter('dias', array());
        $value = $request->getParameter('value', 0);
        
        $user_id = $this->getUser()->getDmUser()->getId();
        foreach (array_keys($months) as $m){
            

            $month = new sfDate($m);
            
            $month->firstDayOfMonth()->clearTime();
            $finalDayOfMonth = $month->copy()->finalDayOfMonth();
            while($month->get()<= $finalDayOfMonth->get()){
                $fdOfWeek = $month->copy()->firstDayOfWeek();
                if ($month->get() > time() && array_key_exists($month->diffDay($fdOfWeek), $days)){
                    if ($value == 'true'){
                        UserAvailabilityTable::addAvailabilityRecord ($user_id, $month->get());
                    }
                    else 
                        UserAvailabilityTable::removeAvailabilityRecord ($user_id, $month->get());
                }
                $month->addDay(1);
            }
        }
        return true;
        }catch(Exception $e){
            echo $e->getMessage(); return false;
        }
    }
    public function executeGetAvailability(sfWebRequest $request){
        try{
            $start = $request->getParameter('start');
            $end= $request->getParameter('end');
            $q = null;
            Doctrine::getTable('UserAvailability')->starting($start, $q)->ending($end, $q)->forUser($this->getUser()->getDmUser(), $q);
            $availabilities = array();
            $avList = $q->execute();
            //die(Doctrine::getTable('EventPublic')->createQuery()->getSqlQuery());
            //print_r($avList->toarray());
            $avDayList = array();
            foreach ($avList as $av){
                $avDayList[$av->getTimestamp()] = 1;
            }
            $start = new sfDate($start);
            $end = new sfDate($end);
            $end = $end->get();
            $cursor = $start->copy();
            $day=-1;
            while($cursor->get() <= $end){
                $day++;
                if (array_key_exists($cursor->get(), $avDayList)) // user is available
                {
                    $avAditionalClasses = array('ev-available-day');
                    $avDescription = array(
                                        'id'=>$cursor->get(),
                                        'title'=>'available',
                                        'start'=>$cursor->dump(),
                                        'end'=>$cursor->dump(),
                                        'className'=>$avAditionalClasses,
                                        'editable'=>false,
                                        'url'=>'',
                                        'allDay'=>true,
                                        'description'=>'',
                                        'type'=>'a',
                                        'day'=>$day,
                                        'enabled'=>$cursor->copy()->addDay(1)->get()>=time()
                                    );
                    
                }
                else{ // user is not available
                    $avAditionalClasses = array('ev-unavailable-day');
                    $avDescription = array(
                                        'id'=>$cursor->get(),
                                        'title'=>'non available',
                                        'start'=>$cursor->dump(),
                                        'end'=>$cursor->dump(),
                                        'className'=>$avAditionalClasses,
                                        'editable'=>false,
                                        'url'=>'',
                                        'allDay'=>true,
                                        'description'=>'',
                                        'type'=>'na',
                                        'day'=>$day,
                                        'enabled'=>$cursor->copy()->addDay(1)->get()>=time()
                                    );
                }
                array_push($availabilities, $avDescription);
                $cursor->addDay(1);
            }
            echo json_encode($availabilities);
          return true;
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }
    }

}
