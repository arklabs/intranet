<?php
/**
 * Asesor components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class assessorComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->assessorPager = $this->getPager($query);
  }


}
