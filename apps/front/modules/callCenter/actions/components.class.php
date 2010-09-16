<?php
/**
 * Call center components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class callCenterComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->callCenterPager = $this->getPager($query);
  }


}
