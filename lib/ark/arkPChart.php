<?php
require_once(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/pChart/pChart.class.php'));
require_once(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/pChart/pData.class.php'));

class arkPChart{
   var $CanvasWidth = 0;
   var $CanvasHeight = 0;
   var $TextAngle = 0;
   var $pData = null;
   var $pChart = null;
   var $ChartPrefix = '';
   var $FileName = null;
   var $GraphTitle = '';
   var $arkPCache = null;
   function arkPChart($title, $chartPrefix = "", $canvasWidth = 1005, $canvasHeight = 275, $textAngle = 25){
     $this->CanvasWidth = $canvasWidth; 
     $this->CanvasHeight = $canvasHeight;
     $this->TextAngle = $textAngle;
     $this->ChartPrefix = ($chartPrefix!='')?$chartPrefix:$this->slugify($title);
     $this->GraphTitle = $title;
     $this->pData = new pData();
     $this->arkPCache = new arkPCache(sfConfig::get('sf_root_dir').'/web/cache/');
     $this->pChart = new pChart($this->CanvasWidth,$this->CanvasHeight);
     $this->pChart->setColorPalette(10,255,0,0);
     $this->pChart->setColorPalette(9,0,255,0);
     $this->pChart->setColorPalette(8,0,0,255);
   }

   public function addSerie($serieName, $serieArray, $isAbsciseLabelSerie = false){
        if (count($serieArray) == 0) {
            $this->FileName = ''; return;
        }
	$this->pData->addPoint($serieArray, $serieName);
        if (!$isAbsciseLabelSerie){
            $this->pData->addSerie($serieName);
	    $this->pData->setSerieName($serieName, $serieName);
        }
        else {
            $this->pData->SetAbsciseLabelSerie($serieName);
	}
		
	
  }
  public function addLabel($serieName, $valueName, $caption,$R=221,$G=230,$B=174){ 
        //$serieName = $this->slugify($serieName);
        $this->pChart->setLabel($this->pData->GetData(),$this->pData->GetDataDescription(),$serieName, $valueName, $caption, $R, $G, $B);
	
   }	
   
   // creates $pChart instance and prepare chart 
   public function draw(){

        if($this->arkPCache->IsInCache($this->ChartPrefix,$this->pData->GetData())){
		$this->FileName = "/cache/".$this->arkPCache->GetHash($this->ChartPrefix,$this->pData->GetData());
	}
        else{

	   	
		$this->pChart->drawGraphAreaGradient(90,90,90,90,TARGET_BACKGROUND);
		$this->pChart->setFontProperties(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/Fonts/tahoma.ttf'),10);
		$this->pChart->setGraphArea(50,30,850,200);
		$this->pChart->drawGraphArea(252,252,252);
		$this->pChart->drawScale($this->pData->GetData(),$this->pData->GetDataDescription(),SCALE_START0,150,150,150,TRUE,$this->TextAngle,0,TRUE);
		$this->pChart->drawGrid(4,TRUE,230,230,230,255);
                $this->pChart->drawOverlayBarGraph($this->pData->GetData(),$this->pData->GetDataDescription());
            }
	  }

   public function render(){
        if(!$this->arkPCache->IsInCache($this->ChartPrefix,$this->pData->GetData())){
            $this->pChart->setFontProperties(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/Fonts/tahoma.ttf'),8);
            $this->pChart->drawLegend(860,30,$this->pData->GetDataDescription(),255,255,255);
            $this->pChart->setFontProperties(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/Fonts/tahoma.ttf'),10);
            $this->pChart->drawTitle(60,22,$this->GraphTitle,255,255,255,585);
            
            $this->arkPCache->WriteToCache($this->ChartPrefix,$this->pData->GetData(),$this->pChart);
            $this->FileName = "/cache/".$this->arkPCache->GetHash($this->ChartPrefix,$this->pData->GetData());
            $this->pChart->Render(sfConfig::get('sf_root_dir').'/web'.$this->FileName);
        }
        return (is_null($this->FileName))? '':$this->FileName;
   }
     
   protected function slugify($text)
   {
	// replace all non letters or digits by -
	$text = preg_replace('/\W+/', '-', $text);
	// trim and lowercase
	$text = strtolower(trim($text, '-'));
	return $text;
   }

}
