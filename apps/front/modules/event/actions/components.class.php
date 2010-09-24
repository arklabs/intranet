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
require_once(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/pChart/pChart.class.php'));
require_once(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/pChart/pData.class.php'));
require_once(dmOs::join(sfConfig::get('sf_root_dir'), 'lib/ark/arkPCache.class.php'));
//require_once(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/pChart/pCache.class.php'));
//require_once(dmOs::join(sfConfig::get('sf_root_dir'), 'lib/ark/arkChart.class.php'));
//require_once('C:\xampp\htdocs\intranet\lib\vendor\diem\dmAdminPlugin\lib\chart\generic\dmChart.php');

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
      Doctrine::getTable('Event')->forUser($userId, $q);
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
    
    $lienzoWidth = 1005;
    $lienzoHeight = 275;
    $textAngle =25;
    $this->FileName = "/cache/perDayDateChart.png";

    $agents = Doctrine::getTable('Agent')->createQuery()->execute();

    $Serie1 = array();
    $Serie2 = array();
    $SeriePorciento = array();
    $SerieX = array();

    $Series = array('SerieX'=>$SerieX,'SerieLabel'=>$SeriePorciento,$Serie1,$Serie2);

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

    // En caso de k no haya datos retornar
    if (count($Serie1)==0){
        $this->FileName = "";
        return;
        }
        
    #DataSet Creation and Fill
    $DataSet = new pData;

    $DataSet->AddPoint($Serie1, "Citas");
    $DataSet->AddPoint($Serie2, "CitasFinalizadas");
    $DataSet->AddPoint($SerieX, "NombreAgentes");
    $DataSet->AddSerie("Citas");
    $DataSet->AddSerie("CitasFinalizadas");
    $DataSet->SetSerieName("Citas Asignadas","Citas");
    $DataSet->SetSerieName("Citas Finalizadas","CitasFinalizadas");
    $DataSet->SetAbsciseLabelSerie("NombreAgentes");
    
    // Cache definition
    $Cache = new arkPCache(sfConfig::get('sf_root_dir').'/web/cache/');
    if($Cache->IsInCache("perDayDateChart",$DataSet->GetData())){
            $this->FileName = "/cache/".$Cache->GetHash("perDayDateChart",$DataSet->GetData());
    }
    else{
    $Test = new pChart($lienzoWidth,$lienzoHeight);
    $Test->drawGraphAreaGradient(90,90,90,90,TARGET_BACKGROUND);
    $Test->setFontProperties(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/Fonts/tahoma.ttf'),10);
    $Test->setGraphArea(50,30,850,200);
    $Test->drawGraphArea(252,252,252);
    $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,150,150,150,TRUE,$textAngle,0,TRUE);
    $Test->drawGrid(4,TRUE,230,230,230,255);
    //$this->Test->setColorPalette(0,255,0,0);

    $Test->drawOverlayBarGraph($DataSet->GetData(),$DataSet->GetDataDescription());

    // Set Labels
    for ($index = 0; $index < count($SerieX); $index++) {
        //$Test->setLabel($DataSet->GetData(),$DataSet->GetDataDescription(),"CitasFinalizadas",$SerieX[$index],round((($Serie2[$index]*100)/max($Serie1[$index],1)),1).'%',221,230,174);
        $Test->setLabel($DataSet->GetData(),$DataSet->GetDataDescription(),"CitasFinalizadas",$SerieX[$index],$SeriePorciento[$index],221,230,174);
    }

    // Finish the graph
    $Test->setFontProperties(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/Fonts/tahoma.ttf'),8);
    //$Test->drawLegend(860,125,$DataSet->GetDataDescription(),255,255,255);
    $Test->drawLegend(860,30,$DataSet->GetDataDescription(),255,255,255);
    $Test->setFontProperties(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/Fonts/tahoma.ttf'),10);
    $Test->drawTitle(60,22,"Citas Asignadas y Finalizadas por Agente",255,255,255,585);
    //$Test->drawFromPNG(sfConfig::get('sf_root_dir').'/web/theme/images/logo-graph.png', 860, 35);
    //$Test->drawFromPNG(sfConfig::get('sf_root_dir').'/web/theme/images/logo.png', 860, 35);

    //$Test->AddBorder(2);
    $Cache->WriteToCache("perDayDateChart",$DataSet->GetData(),$Test);
    $this->FileName = "/cache/".$Cache->GetHash("perDayDateChart",$DataSet->GetData());
    $Test->Render(sfConfig::get('sf_root_dir').'/web/cache/'.$Cache->GetHash("perDayDateChart",$DataSet->GetData()));
    //$Test->Render(sfConfig::get('sf_root_dir').'/web/cache/perDayDateChart.png');
    }
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
 
