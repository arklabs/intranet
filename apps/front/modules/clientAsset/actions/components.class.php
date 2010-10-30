<?php
/**
 * Activo del cliente components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class clientAssetComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->clientAssetPager = $this->getPager($query);
  }


}
