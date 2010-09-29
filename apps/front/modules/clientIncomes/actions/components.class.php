<?php
/**
 * Ingresos components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class clientIncomesComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->clientIncomesPager = $this->getPager($query);
  }


}
