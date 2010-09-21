<?php
/**
 * Main actions
 */
class mainActions extends myFrontModuleActions
{
    public function preExecute()
    {
        parent::preExecute();
        $this->forwardSecureUnless($dmUser = $this->getUser()->getDmUser());
    }
    public function executeTest(sfWebRequest $request){
        $this->getUser()->setFlash('notice', 'calendar loaded');
        return $this->renderPartial('main/demoContent');
    }
    public function executeRenderComponent(sfWebRequest $request){
        $componentModule = $request->getParameter('mod');
        $componentName = $request->getParameter('name');
        if (!is_null($componentName) && $componentName!='' && !is_null($componentModule) && $componentModule!='')
            return  $this->renderComponent($componentModule, $componentName);
        else
            return true;
    }
    public function executeRenderPartial(sfWebRequest $request){
        
        $partialModule = $request->getParameter('module');
        $partialName = $request->getParameter('name');
        if (!is_null($partialName) && $partialName!='' && !is_null($partialModule) && $partialModule!='')
            return $this->renderPartial(sprintf('%s/%s',$partialModule, $partialName));
        else
            return '';
    }
    public function executeGetEvents(sfWebRequest $request){
            $start = $request->getParameter('start');
            $end= $request->getParameter('end');
           // $user = $this->getUser()->getId();
            $user = 7;
            $start = date('Y-m-d h:i', $start);
            $end = date('Y-m-d h:i', $end);
            $q = Doctrine::getTable('Event')->createQuery();
            //$q = Doctrine::getTable('Event')->getActiveEventsA($q);
            $q = Doctrine::getTable('Event')->starting($start, $q);
            $q = Doctrine::getTable('Event')->ending($end, $q);
            $q = Doctrine::getTable('Event')->forUser($user, $q);
            //echo $q->getSqlQuery();die;
            $events = array();
            $eventList = $q->execute();
            foreach ($eventList as $event){
                $tmp = array($event->asArray());
                array_push($events, $event->asArray());
            } 
            echo json_encode($events);
        return true;
    }
    public function executeRenderEventDetailsOrForm(sfWebRequest $request){
        $id = $request->getParameter('id');
        return $this->renderPartial('fullCalendarEventDemoContent', array('id'=>$id));
    }

    public function executeRenderGeoLocation(sfWebRequest $request){
      $address = urldecode($this->getRequest()->getParameter('address','Los Angeles, California'));
      return $this->renderPartial('geolocalizador', array('address'=>$address));
    }
    
}
