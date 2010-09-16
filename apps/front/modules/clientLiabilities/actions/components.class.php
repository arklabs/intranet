<?php
/**
 * Client liabilities components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class clientLiabilitiesComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->clientLiabilitiesPager = $this->getPager($query);
  }


}
