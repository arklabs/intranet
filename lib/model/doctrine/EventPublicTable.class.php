<?php


class EventPublicTable extends EventRootTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EventPublic');
    }
    public function starting($start, Doctrine_Query &$q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('Event e');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.date_start >= ?', $alias), $start);

        return $this;
    }

    public function ending($end, Doctrine_Query &$q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('Event e');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.date_start <= ?', $alias), $end);

        return $this;
    }

    public function forUser($dmUserInstance, Doctrine_Query &$q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('Event e');
        }
        // getting public user id
        $publicUser = Doctine::getTable('DmUser')->createQuery('u')->where('u.username = ?','public')->fetchOne();

        $alias = $q->getRootAlias();
        $user_id = $publicUser->getId();
        
        $q->addWhere(sprintf('%s.dm_user_id = ?', $alias), $user_id);
        
        return $this;
    }


}