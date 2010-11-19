<?php
/**
 * Evento actions
 * 
 */
class eventActions extends myFrontModuleActions
{

    public static $_eventsConfig = null;

  protected function loadEventsConfiguration()
  {
    $configFile = sfConfig::get('sf_app_dir') . '/config/eventsConfig.yml';
        if (!file_exists($configFile)) {
            return;
        }
        self::$_eventsConfig = sfYaml::load($configFile);
  }

  public function executeGetEventBasics(sfWebRequest $request){
      $eventId = $request->getParameter('event-id', NUll);
      if (!$eventId) {
          echo ''; return true;
      }
      $event = Doctrine::getTable('Event')->findById($eventId);
      if (!count($event)){
          echo ''; return true;
      }
      echo $event[0]->buildEventInformationBasics();
      return true;

  }
  public function executeGetMyEvents(sfWebRequest $request)
  {
    try{
            if (is_null(self::$_eventsConfig))
                $this->loadEventsConfiguration();
            $evCategoriesConfig = self::$_eventsConfig['events']['categories'];
            $evStatusConfig = self::$_eventsConfig['events']['status'];
            $start = $request->getParameter('start');
            $end= $request->getParameter('end');
            $start = date('Y-m-d h:i', $start);
            $end = date('Y-m-d h:i', $end);
            $user = $this->getUser()->getDmUser()->getId();
            $q = null;
            Doctrine::getTable('Event')->starting($start, $q)->ending($end, $q)->forUser($this->getUser()->getDmUser(), $q);
            
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
                                        'title'=>$event->getClient()->__toString(),
                                        'start'=>$event->getDateStart(),
                                        'end'=>$event->getDateEnd(),
                                        'className'=>$eventAditionalClasses,
                                        'editable'=>(($event->getCreatedBy()->getId()!=$user)?false:true),
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
  
  public function executeViewEvent(sfWebRequest $request)
  {
    $eventId = $request->getParameter('id');
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

  public function executeNewEvent(sfWebRequest $request)
  {
    try{
        $dateStart = $request->getParameter('date','');
        
        if ($dateStart != ''){
           $dateStart= new sfDate($dateStart);
           $dateStart = $dateStart->addMonth(1);
           $dateEnd = $dateStart->copy()->dump();
           $dateStart = $dateStart->dump();
        }
        else {
            $dateStart= new sfDate(time());
            $dateStart = $dateStart->addDay(1)->clearTime();
            $dateEnd = $dateStart->copy()->dump();
            $dateStart = $dateStart->dump();
        }
        $eventId = $request->getParameter('id', '');
        
        if ($eventId==''){
            $event = new Event();
            $event->setDateStart($dateStart);
            $event->setDateEnd($dateEnd);
            $action = '/index.php/+/event/createEvent';
        }
        else{
            $event = Doctrine::getTable('Event')->findById($eventId);
            $event = $event[0];
            $event->setIsNew(0); $event->save();
            $action = '/index.php/+/event/updateEvent';
        }
        
        $eventForm = new EventForm($event);
        return $this->renderPartial('newEvent',array('form'=>$eventForm, 'action'=>$action, 'lockDates'=>0));
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }
  }
  public function executeBatchChangeStatus(sfWebRequest $request){
      try{
          
      $ids = $request->getParameter('ids');
      $status = $request->getParameter('status');
      $events = Doctrine_Query::create()->from('Event e')->whereIn('e.id', $ids)->execute();
      $user_id= $this->getUser()->getDmUser()->getId();
      foreach ($events as $e){
          if ($e->get('dm_user_id') == $user_id){
              $e->set('status_id',$status);
              $e->save();
          }
      }
      $this->redirect('/mis-eventos');
      }catch(Exception $e){
          echo $e->getMessage();
      }
      
  }
  public function executeUpdateEvent(sfWebRequest $request)
  {
    try{
            $clickedButton = $request->getParameter('clicked_button');
            $data= $request->getParameter('data','nothing');
            $data = $data['form'];
            $data['dm_user_id'] = $this->getUser()->getDmUser()->getId();
            $event = Doctrine::getTable('Event')->findById($data['id']);
            if ($event[0]->getEventStatus() == 'Concluido'){
                $this->getUser()->setFlash('error', 'El evento ya ha sido cerrado. Esta operaci&oacute;n no es v&aacute;lida');
                echo '';
                die;
            }
            
            if ($event[0]->getUpdatedBy()->getId()!= $data['dm_user_id']){
                
                $data['category_id']= $event[0]->getEventCategory()->getId();
                $data['title']= $event[0]->getTitle();
                
                $data['description']= $event[0]->getDescription();
                $data['date_start']= $event[0]->getDateStart();
                
                if ($data['status_id']=='Concluido' && $data['date_end']==''){
                    $tmp = new sfDate(time());
                    $data['date_end']=  $tmp->dump();
                }
                $this->getUser()->setFlash('error', 'Como Ud no es propietario del evento, esta operaci&oacute;n solo se aplic&oacute; al campo "Estado"');
            }
            $data['updated_by'] = $data['dm_user_id'];
            $data['created_by'] = $event[0]->getCreatedBy()->getId();
            $data['is_active'] = 1;
            
            //$data['created_by'] = $event[0]->getCreatedBy();
            
            $eventForm = new EventForm($event[0]);
            $eventForm->bind($data);
            if ($eventForm->isValid()){
                $eventForm->save();
                $this->getUser()->setFlash('notice', 'El evento ha sido actualizado satisfactoriamente.');
                
                if ($clickedButton=='Enviar y crear otro'){
                        return $this->executeNewEvent($request);//renderPartial('newEvent',array('form'=>new EventForm(),  'action' => '/index.php/+/event/createEvent'));
                }
                echo ''; return true;
            }
            else {
                return $this->renderPartial('newEvent',array('form'=>$eventForm,  'action' => '/index.php/+/event/updateEvent', 'lockDates'=>($data['created_by'] == $data['updated_by'])));die;
            }
            
        }catch(Exception $e){
            echo $e->getMessage(); die;
        }
  }

  public function executeCreateEvent(sfWebRequest $request)
  {
    try{
            $clickedButton = $request->getParameter('clicked_button');
            $data= $request->getParameter('data','nothing');
            $data = $data['form'];
            $data['dm_user_id'] = $this->getUser()->getDmUser()->getId();
            //$event = new Event($data);
            $data['created_by'] = $data['dm_user_id'];
            $data['updated_by'] = $data['dm_user_id'];
            $data['is_active'] = 1;
            $eventForm = new EventForm();
            $eventForm->bind($data);
            if ($eventForm->isValid()){
                $eventForm->save();
                if ($clickedButton=='Enviar y crear otro')
                    return $this->renderPartial('newEvent',array('form'=>new EventForm(),'action' => '/index.php/+/event/createEvent', 'lockDates'=>0));
                $this->getUser()->setFlash('notice', 'Ha creado un nuevo evento satisfactoriamente.');
                echo ''; die;
            }
            else {
                return $this->renderPartial('newEvent',array('form'=>$eventForm, 'action' => '/index.php/+/event/createEvent'));
            }
        }catch(Exception $e){
            echo $e->getMessage(); die;
        }
  }

  public function executeFormWidget(dmWebRequest $request)
  {
    $form = new EventForm();
        
    if ($request->hasParameter($form->getName()) && $form->bindAndValid($request))
    {
      $form->save();
      $this->redirectBack();
    }
    
    $this->forms['Event'] = $form;
  }

  public function executeExportToPdf(dmWebRequest $request)
  {
      $config = sfTCPDFPluginConfigHandler::loadConfig();
  //sfTCPDFPluginConfigHandler::includeLangFile($this->getUser()->getCulture());

  $doc_title    = "test title";
//  $doc_subject  = "test description";
//  $doc_keywords = "test keywords";
    $htmlcontent  = $this->getComponent('event', 'PerMonthDateReport');
    $citas = Doctrine::getTable('Event')->getActiveCitasR();


  //create new PDF document (document units are set by default to millimeters)
  $pdf = new sfTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

  // set document information
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor(PDF_AUTHOR);
  //$pdf->SetTitle($doc_title);
//  $pdf->SetSubject($doc_subject);
//  $pdf->SetKeywords($doc_keywords);

  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

  //set margins
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

  //set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor

  $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

  //initialize document
  $pdf->AliasNbPages();
  $pdf->AddPage();

  // set barcode
  $pdf->SetBarcode(date("Y-m-d H:i:s", time()));

    //titulo del reporte
    $pdf->writeHTML($htmlcontent); //(10, 'Listado de citas por agente', '', 0, 'C',1);

    

    //$this->renderCompone
   // $pdf->writeHTML( , true, 0);
  // Close and output PDF document
  //$pdf->Output();
  //return sfView::NONE;
    $pdf->SetCompression(true);

    $this->setLayout('pdf');

    sfConfig::set('sf_web_debug', false);

    $this->pdf = $pdf;
  }
  public function executeRefreshStatus(sfWebRequest $request){
  	  try{
  		$eventId = $request->getParameter('ev-id');
  		if (!$eventId) return false;
  		 
  		$event = Doctrine::getTable('Event')->findById($eventId);
  		$event = $event[0];
  		if (!$event)  return false;
  		
  		if ($event->getEventStatus()!="Pendiente" && $event->getDmUserId()!=''){
  			$user = $event->getDmUser();
  			echo $this->getHelper()->tag('label.assigned', 'Asignado a '.$user[0]);
  			return true;
  		} 
  		else
  		{
  			$response = $this->getHelper()->tag('label.pending', 'Pendiente');
  			$response.= $this->getHelper()->open('select.ev-asign-agent-list', array('id'=>$event->getId()));
  			$agents = Doctrine::getTable('Agent')->createQuery()->execute();
  			$response.= $this->getHelper()->open('option',array('value'=>-1)).'Seleccione un agente'.$this->getHelper()->close('option');
  			foreach ($agents as $a){
  				$response.= $this->getHelper()->open('option', array('value'=>$a->getId())).$a.$this->getHelper()->close('option');
  			}
  			$response.= $this->getHelper()->close('select');
  			$response.='<img src="/theme/images/loader-small.gif" style="display: none; float: right;" class="ev-status small-loader" id="'.$event->getId().'">';
  			echo $response; 
  			return true;
  		}
  		return true;
  	  }catch(Exception $e){
  	  	echo $e->getMessage(); return false;
  	  }
  }
  public function executeChangeDate(sfWebRequest $request){
  	 try{
	  	  $eventId = $request->getParameter('event');
	  	  if (!$eventId) return false;
	  	  $newDate = $request->getParameter('new-date');
	  	  if (!$newDate) return false;

	  	  $event = Doctrine::getTable('Event')->findById($eventId);
	  	  $event = $event[0];
	  	  $event->setDateStart($newDate);
	  	  $event->setDateEnd($newDate);
	  	  $event->setStatusId(EventStatus::getAssignedStatus()->getId());
	  	  $event->save();
	  	  return true;
  	 }catch(Exception $e){
  	 	echo $e->getMessage(); return false;
  	 }
  }
  public function executeAssignAgent(sfWebRequest $request){
  		$eventId = $request->getParameter('ev-id');
  		if (!$eventId) return false;
  		 
  		$event = Doctrine::getTable('Event')->findById($eventId);
  		$event = $event[0];
  		
  		$agentId = $request->getParameter('ag-id');
  		
  		$agent = Doctrine::getTable('Agent')->findById($agentId);
  		$agent = $agent[0];
  		
  		try{
  			$event->setDmUserId($agent->getId());
  			$eventStatusAssigned = Doctrine::getTable('EventStatus')->findByName('Asignado');
  			$event->setStatusId($eventStatusAssigned[0]->getId());
  			$event->save();
  			echo '';
  			return true;
  		}catch(Exception $e){echo $e->getMessage(); return false;}
  }

}
