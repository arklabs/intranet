<?php
/**
 * Gastos components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class clientExpensesComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->clientExpensesPager = $this->getPager($query);
  }


}
