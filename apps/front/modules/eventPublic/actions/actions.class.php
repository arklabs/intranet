<?php
/**
 * Evento P&uacute;blico actions
 */
class eventPublicActions extends myFrontModuleActions
{

   public function executeGetMyEvents(sfWebRequest $request)
  {
    try{
            $start = $request->getParameter('start');
            $end= $request->getParameter('end');
            $start = date('Y-m-d h:i', $start);
            $end = date('Y-m-d h:i', $end);
            $user = $this->getUser()->getDmUser()->getId();
            $q = null;
            Doctrine::getTable('EventPublic')->starting($start, $q)->ending($end, $q)->forUser($this->getUser()->getDmUser(), $q);

            $events = array();
            $eventList = $q->execute();
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
                                        'editable'=>(($this->getUser()->getDmUser()->hasPermission('editPublicCalendarEvents_front') || $this->getUser()->getDmUser()->isSuperAdmin())?true:false),
                                        //'url'=>'/index.php/+/event/newEvent?id='.$event->getId(), facebox mode
                                        'url'=>'/admin.php/+/event/edit/pk/'.$event->getId().'/dm_embed/1',
                                        /*'url'=>$this->getHelper()->link('app:admin/+/event/edit')->params(array(
                                                                        'pk'        => $event->getId(),
                                                                        'dm_embed'  => 1
                                                                      )), */
                                        'allDay'=>($tmp->getHour() == 0)?true:false,
                                        'description'=>$event->buildEventInformationBasics(),
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
}
