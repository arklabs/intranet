<?php
/**
 * Gasto del cliente components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class clientExpenseComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->clientExpensePager = $this->getPager($query);
  }


}
