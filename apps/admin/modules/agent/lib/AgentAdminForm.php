<?php

/**
 * agent admin form
 *
 * @package    intranet
 * @subpackage agent
 * @author     Your name here
 */
class AgentAdminForm extends BaseAgentForm
{
  public function configure()
  {
    parent::configure();
  }

  public function save($conn = null){
      
      $record = parent::save($conn);
      if ($this->isNew()){
          $group = Doctrine::getTable('DmGroup')->findByName('agentes');
          $record->getId();
          if ($group->count() == 0) {
              $group = new DmGroup();
              $group->setName('agentes');
              $group->setDescription('Todos los Agentes pertenecen por defecto a este grupo.');
              $group_id = $group->save();
          }
          else
              $group_id = $group->getFirst()->getId();

          $this->addUserToGroup($this->getObject()->getId(), $group_id);
      }
      return $record;
  }

  public function addUserToGroup($userId, $groupId){
       $userGroupRecord = Doctrine::getTable('DmUserGroup')->createQuery('g')->where('g.dm_user_id = ?', $userId)->addWhere('g.dm_group_id = ?', $groupId)->execute();
       if (is_null($userGroupRecord) || $userGroupRecord->count() == 0){
           $record = Doctrine::getTable('DmUserGroup')->create(array(
               'dm_user_id'=>$userId,
               'dm_group_id'=>$groupId,
           ));
           $record->save();
       }
  }
  
}