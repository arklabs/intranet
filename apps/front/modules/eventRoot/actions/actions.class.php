<?php
/**
 * Reuni&oacute;n &oacute; Recordatorio actions
 */
class eventRootActions extends myFrontModuleActions
{
  public function executeGetEventBasics(sfWebRequest $request){
       $eventId = $request->getParameter('event-id');
       $event = Doctrine::getTable('EventRoot')->findById($eventId);
       if (count($event)){
          $event = $event[0];
       }
       echo $event->buildEventInformationBasics();
       return true;
   }
  public function executeGetMyEvents(sfWebRequest $request)
  {
    try{
            $start = $request->getParameter('start');
            $end= $request->getParameter('end');
            $start = date('Y-m-d h:i', $start);
            $end = date('Y-m-d h:i', $end);
            $q = null;
            //$eventList = Doctrine::getTable('EventRoot')->createQuery()->execute();
            $user = $this->getUser()->getDmUser()->getId();
            Doctrine::getTable('EventRoot')->starting($start, $q)->ending($end, $q)->forUser( $this->getUser()->getDmUser(), $q);
            $events = array();
            
            $eventList = $q->execute();
            //die(Doctrine::getTable('EventRoot')->createQuery()->getSqlQuery());
            //print_r($eventList->toarray());
            foreach ($eventList as $event){
                $eventAditionalClasses =array();
                array_push($eventAditionalClasses,$event->getEventStatus()->getCssClassList());
                array_push($eventAditionalClasses,$event->getEventCategory()->getCssClassList());
                if ($event->getIsNew() == '1')
                    array_push($eventAditionalClasses, 'ev-new');


                $tmp = new sfDate($event->getDateStart());
                $eventDescription = array(
                                        'id'=>$event->getId(),
                                        'title'=>$event->getTitle(),
                                        'start'=>$event->getDateStart(),
                                        'end'=>$event->getDateEnd(),
                                        'className'=>$eventAditionalClasses,
                                        'editable'=>(($event->getCreatedBy()->getId()!=$user)?false:true),
                                        //'url'=>'/index.php/+/event/newEvent?id='.$event->getId(), facebox mode
                                        'url'=>'/admin.php/+/eventRoot/edit/pk/'.$event->getId().'/dm_embed/1',
                                        /*'url'=>$this->getHelper()->link('app:admin/+/event/edit')->params(array(
                                                                        'pk'        => $event->getId(),
                                                                        'dm_embed'  => 1
                                                                      )), */
                                        'allDay'=>($tmp->getHour() == 0)?true:false,
                                        'description'=>$event->getDescription(),
                                    );
                array_push($events, $eventDescription);
            }
            echo json_encode($events);
          return true;
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }
  }
  public function executeMoveEvent(sfWebRequest $request)
  {
    try{
            $eventId = $request->getParameter('id');
            $dayDelta = $request->getParameter('dayDelta');
            $minuteDelta = $request->getParameter('minuteDelta');
            echo $dayDelta;
            $event = Doctrine::getTable('EventRoot')->findById($eventId);
            $event = $event[0];
            $dateStart = new sfDate($event->getDateStart());
            $dateStart->addDay($dayDelta);
            $dateStart->addMinute($minuteDelta);
            if (!is_null($event->getDateEnd())){
                $dateEnd = new sfDate($event->getDateEnd());
                $dateEnd->addDay($dayDelta);
                $dateEnd->addMinute($minuteDelta);
                $event->setDateEnd($dateEnd->dump());
            }
            $event->setDateStart($dateStart->dump());

            $event->save();
            $this->getUser()->setFlash('notice', 'El evento ha sido cambiado satisfactoriamente.');
            return true;
        }
        catch(Exception $e){
            $this->getUser()->setFlash('error', 'Ha ocurrido un error mientras se movía el evento. Esta operación no surtirá efecto.');
            return false;
        }
  }

  public function executeChangeEnd(sfWebRequest $request)
  {
    try{
            $eventId = $request->getParameter('id');
            $dayDelta = $request->getParameter('dayDelta');
            $minuteDelta = $request->getParameter('minuteDelta');
            echo $dayDelta;
            $event = Doctrine::getTable('EventRoot')->findById($eventId);
            $event = $event[0];
           if (!is_null($event->getDateEnd())){
                $dateEnd = new sfDate($event->getDateEnd());
            }
            else {
                $dateEnd = new sfDate($event->getDateStart());
            }
            $dateEnd->addDay($dayDelta);
            $dateEnd->addMinute($minuteDelta);
            $event->setDateEnd($dateEnd->dump());
            $event->save();
            $this->getUser()->setFlash('notice', 'El evento ha sido cambiado satisfactoriamente.');
            return true;
        }
        catch(Exception $e){
            $this->getUser()->setFlash('error', 'Ha ocurrido un error mientras se movía el evento. Esta operación no surtirá efecto.');
            return false;
        }
  }

}
