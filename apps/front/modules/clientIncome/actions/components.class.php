<?php
/**
 * Ingresos del cliente components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class clientIncomeComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->clientIncomePager = $this->getPager($query);
  }


}
