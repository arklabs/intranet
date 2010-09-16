<?php


class ClientTable extends myDoctrineTable
{
	
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Client');
    }
	
    public function getSummary($clientId){
    	 return Doctrine::getTable('Client')
            ->createQuery('c')
            ->leftJoin('c.ClientFile cf')
            ->leftJoin('c.Agent agent')
            ->leftJoin('c.House house')
            ->leftJoin('c.CreatedBy createdBy')
            ->leftJoin('c.Employment employment')
            ->leftJoin('c.ClientExpenses expense')
            ->leftJoin('c.ClientIncomes income')
            ->leftJoin('c.ClientAssets assets')
            ->leftJoin('c.ClientLiabilities liability')
            //->leftJoin('c.Property property')
            ->leftJoin('c.UpdatedBy updatedBy')
            ->where('c.id = ?', $clientId)
            ->fetchOne();
    }
    protected function listClientsForAgents($userId){
        $q = Doctrine::getTable('Client')
            ->createQuery('c')
            ->leftJoin('c.ClientFile cf')
            ->leftJoin('c.Event e')
            ->leftJoin('c.Agent assignedTo')
            ->leftJoin('c.House liveIn')
            ->where('e.dm_user_id = ?', $userId)
            ->orWhere('cf.assigned_to = ?', $userId)
            ->orWhere('c.created_by = ?', $userId);
        return $q;
    }
    public function listClientsForUser($sfUser){
        if ($sfUser->hasGroup('agentes')){
            return $this->listClientsForAgents($sfUser->getId());
        }
        else{
            return Doctrine::getTable('Client')
            ->createQuery('c')
            ->leftJoin('c.Agent assignedTo')
            ->leftJoin('c.House liveIn')
            ->where('c.created_by = ?', $sfUser->getId());
        }
    }

    public function fetchClientsForAutoComplete($sfUser){
        $q = Doctrine::getTable('Client')->create();
        return $q;
    }

    public function getClientsAssignedTo($user_id, Doctrine_Query $q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('Client e');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.assigned_to = ?', $alias), $user_id);

        return $q;
    }
    
    public function like($pattern, Doctrine_Query $q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('Client e');
        }
        $alias = $q->getRootAlias();

        $q->addWhere(sprintf('%s.first_name LIKE "%s" OR %s.last_name LIKE "%s"', $alias, "%".$pattern."%", $alias, "%".$pattern."%"));

        return $q;
    }
}