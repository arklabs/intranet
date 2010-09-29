<?php
/**
 * Fraseolog&iacute;a components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class phraseologyComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->phraseologyPager = $this->getPager($query);
  }


}
