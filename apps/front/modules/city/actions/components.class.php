<?php
/**
 * City components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 */

require_once(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/pChart/pChart.class.php'));
require_once(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/pChart/pData.class.php'));
require_once(dmOs::join(sfConfig::get('sf_root_dir'), 'lib/ark/arkPCache.class.php'));

class cityComponents extends myFrontModuleComponents
{

  public function executeDatesByCity()
  {
    $this->showColumns = array(
          'Agent'=>array('label'=>'Ciudad', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
          'num_events'=>array('label'=>'Citas', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'num_fevents'=>array('label'=>'Finalizadas', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'efectividad'=>array('label'=>'Efectividad', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
      );
      $dstart = $this->getRequest()->getParameter('dateStart');
      $dend = $this->getRequest()->getParameter('dateEnd');
      $agents = Doctrine::getTable('City')->createQuery()->execute();
      $this->listArray = array();
      foreach($agents as $agent)
      {
          $dates = $agent->getActiveEventsByCategoryQuery(1, null, $dstart, $dend);
          $agentDates = $dates->count();
          $agentDatesFinished = $agent->countActiveEventsByStatus(2,$dates, null, null);
          array_push($this->listArray, array(
              'Agent' => $agent,
              'num_events' => $agentDates,
              'num_fevents' => $agentDatesFinished,
              'efectividad' => (($agentDatesFinished*100)/max($agentDates,1)).'%'
          ));
      }
  }

  public function executeDatesByCityChart()
  {
    $startingDate = $this->getRequest()->getParameter('dateStart').' 00:00:00';
    $endingDate = $this->getRequest()->getParameter('dateEnd').' 00:00:00';

    $lienzoWidth = 1005;
    $lienzoHeight = 275;
    $textAngle =25;
    $this->FileName = "/cache/datesByCityChart.png";

    $agents = Doctrine::getTable('City')->createQuery()->execute();

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
    $DataSet->AddPoint($SerieX, "NombreCiudades");
    $DataSet->AddSerie("Citas");
    $DataSet->AddSerie("CitasFinalizadas");
    $DataSet->SetSerieName("Citas Asignadas","Citas");
    $DataSet->SetSerieName("Citas Finalizadas","CitasFinalizadas");
    $DataSet->SetAbsciseLabelSerie("NombreCiudades");

    // Cache definition
    $Cache = new arkPCache(sfConfig::get('sf_root_dir').'/web/cache/');
    if($Cache->IsInCache("datesByCityChart",$DataSet->GetData())){
            $this->FileName = "/cache/".$Cache->GetHash("datesByCityChart",$DataSet->GetData());
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
    $Test->drawTitle(60,22,"Citas Asignadas y Finalizadas por Ciudad",255,255,255,585);
    //$Test->drawFromPNG(sfConfig::get('sf_root_dir').'/web/theme/images/logo-graph.png', 860, 35);
    //$Test->drawFromPNG(sfConfig::get('sf_root_dir').'/web/theme/images/logo.png', 860, 35);

    //$Test->AddBorder(2);
    $Cache->WriteToCache("datesByCityChart",$DataSet->GetData(),$Test);
    $this->FileName = "/cache/".$Cache->GetHash("datesByCityChart",$DataSet->GetData());
    $Test->Render(sfConfig::get('sf_root_dir').'/web/cache/'.$Cache->GetHash("datesByCityChart",$DataSet->GetData()));
    //$Test->Render(sfConfig::get('sf_root_dir').'/web/cache/perDayDateChart.png');
    }
  }


}
