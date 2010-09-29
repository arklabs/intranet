<?php
/**
 * Empleado components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class employmentComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->employmentPager = $this->getPager($query);
  }


}
