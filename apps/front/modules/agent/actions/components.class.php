<?php
/**
 * Agente components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */

require_once(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/pChart/pChart.class.php'));
require_once(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/pChart/pData.class.php'));
require_once(dmOs::join(sfConfig::get('sf_root_dir'), 'lib/ark/arkPCache.class.php'));

class agentComponents extends myFrontModuleComponents
{
    public function executeDatesByAgent(){
      $this->showColumns = array(
          'Agent'=>array('label'=>'Agente', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string', 'link_to_object'=>false),
          'num_events'=>array('label'=>'Cantidad de Citas', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'num_fevents'=>array('label'=>'Citas Finalizadas', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
          'efectividad'=>array('label'=>'Efectividad', 'href'=>'', 'extra_clases'=>'', 'is_relation'=>0, 'type'=>'string'),
      );
      $dstart = $this->getRequest()->getParameter('dateStart');
      $dend = $this->getRequest()->getParameter('dateEnd');
      
      $agents = Doctrine::getTable('Agent')->getWithEvents($dstart, $dend);
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
          //print_r($agentDates->toarray());
      	  //print_r($agentDatesFinished->toarray());
      	  
      }

      

      //$this->setTemplate('_reportList.php');
    }
}
