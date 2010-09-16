<?php
/**
 * Client file components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 */
class clientFileComponents extends myFrontModuleComponents
{

  public function executeFullcalendar()
  {
    // Your code here
  }
  protected function getAgentQuery(){
      $userId = ($this->getUser()->getGuardUser())?$this->getUser()->getGuardUser()->getId():-1;
      return $this->getListQuery('cf')
                ->leftJoin('cf.Agent assignedTo')
                ->andWhere('cf.assigned_to = ?', $userId)
                ->orWhere('cf.created_by = ?', $userId)
                ->limit(200);
  }
  protected function getOtherQuery(){
      $user = $this->getUser();
        $department = '1';
        if ($user->hasGroup('procesamiento')){
            $department = 'Procesamiento';
        }
        elseif ($user->hasGroup('contabilidad')){
            $department = 'Contabilidad';
        }
        elseif ($user->hasGroup('recepcionista')){
            $department = 'Recepcionista';
        }
        elseif ($user->hasGroup('estructuracion')){
            $department = 'Estructuracion';
        }
       $q = $this->getListQuery('cf')
                ->leftJoin('cf.Department d')
                ->andWhere('d.name = ?', $department);
       return $q;
  }
  public function executeList()
  {
    $userId = ($this->getUser()->getGuardUser())?$this->getUser()->getGuardUser()->getId():-1;
    if ($this->getUser()->hasgroup('agentes'))
        $query = $this->getAgentQuery();
    else
        $query = $this->getOtherQuery();
    
    $this->clientFilePager = $this->getPager($query);
  }


}
