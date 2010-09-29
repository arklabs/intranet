<?php
/**
 * Client file actions
 */
class clientFileActions extends myFrontModuleActions
{
  public static $_eventsConfig = null;

  protected function loadEventsConfiguration()
  {
    $configFile = sfConfig::get('sf_app_dir') . '/config/filesConfig.yml';
        if (!file_exists($configFile)) {
            return;
        }
        self::$_eventsConfig = sfYaml::load($configFile);
  }
  protected function getQueryForAgentGroup($start, $end){
        $user = $this->getUser()->getDmUser()->getId();
        $q = Doctrine::getTable('ClientFile')->createQuery();
        //$q = Doctrine::getTable('Event')->getActiveEventsA($q);
        $q = Doctrine::getTable('ClientFile')->starting($start, $q);
        $q = Doctrine::getTable('ClientFile')->ending($end, $q);
        $q = Doctrine::getTable('ClientFile')->forUser($user, $q);
        return $q;
  }
  protected function getQueryForDepartment($start, $end){
      
        $user = $this->getUser();
        $department = '';
        if ($user->hasGroup('procesamiento')){

            $department = 'Procesamiento';
        }
        elseif ($user->hasGroup('contabilidad')){
            $department = 'Contabilidad';
        }
        elseif ($user->hasGroup('recepcionista')){
            $department = 'Recepcionista';
        }
        elseif ($user->hasGroup('estructuracion')){
            $department = 'Estructuracion';
        }

        $q = Doctrine::getTable('ClientFile')->createQuery();
        $q = Doctrine::getTable('ClientFile')->starting($start, $q);
        $q = Doctrine::getTable('ClientFile')->ending($end, $q);
        $q = Doctrine::getTable('ClientFile')->forDepartmentName($department, $q);
        return $q;
  }
  public function executeGetMyEvents(sfWebRequest $request)
  {
    try{
            if (is_null(self::$_eventsConfig))
                $this->loadEventsConfiguration();
            $evFilesConfig = self::$_eventsConfig['events']['files'];
            $evDepartmentsConfig = self::$_eventsConfig['events']['departments'];

            $start = $request->getParameter('start');
            $end= $request->getParameter('end');
            $start = date('Y-m-d h:i', $start);
            $end = date('Y-m-d h:i', $end);

            $q = null;
            if ($this->getUser()->hasGroup('agentes')){
                $q = $this->getQueryForAgentGroup ($start, $end);
            }
            else 
               $q = $this->getQueryForDepartment($start, $end);

            $events = array();
            $eventList = $q->execute();
            foreach ($eventList as $event){
                $eventAditionalClasses =array();
                array_push($eventAditionalClasses, $event->getDepartment()->getCssClassList());
                array_push($eventAditionalClasses, $event->getFileType()->getCssClassList());

                $tmp = new sfDate($event->getDateStart());
                $eventDescription = array(
                                        'id'=>$event->getId(),
                                        'title'=>$event->getClient()->__toString(),
                                        'start'=>$event->getDateStart(),
                                        'end'=>$event->getDateEnd(),
                                        'className'=>$eventAditionalClasses,
                                        'editable'=>false,
                                        'url'=>'/admin.php/+/clientFile/edit/pk/'.$event->getId().'/dm_embed/1',
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
        //echo $q->getSqlQuery(); die;
  }
  public function executeMoveEvent(sfWebRequest $request)
  {
    try{
            $eventId = $request->getParameter('id');
            $dayDelta = $request->getParameter('dayDelta');
            $minuteDelta = $request->getParameter('minuteDelta');
            echo $dayDelta;
            $event = Doctrine::getTable('Event')->findById($eventId);
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
            $event = Doctrine::getTable('Event')->findById($eventId);
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

?>
