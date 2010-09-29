<?php
/**
 * Agente Externo components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class externalAgentComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = Doctrine::getTable('ExternalAgent')->createQuery();
    
     $this->externalAgentPager = $this->getPager($query);
  }


}
