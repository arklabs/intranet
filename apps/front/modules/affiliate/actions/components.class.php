<?php
/**
 * Afiliado components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class affiliateComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $userId = ($this->getUser()->getGuardUser())?$this->getUser()->getGuardUser()->getId():-1;
    $query = Doctrine::getTable('Affiliate')->createQuery();
    $this->affiliatePager = $this->getPager($query);
    
  }


}
