<?php
/**
 * Activos components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class clientAssetsComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->clientAssetsPager = $this->getPager($query);
  }


}
