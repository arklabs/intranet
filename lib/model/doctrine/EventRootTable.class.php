<?php


class EventRootTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EventRoot');
    }

    public function starting($start, Doctrine_Query &$q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('EventRoot e');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.date_start >= ?', $alias), $start);

        return $this;
    }

    public function ending($end, Doctrine_Query &$q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('EventRoot e');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.date_start <= ?', $alias), $end);

        return $this;
    }
    
    public function forUser($dmUserInstance, Doctrine_Query &$q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('EventRoot e');
        }
        $alias = $q->getRootAlias();
        $user_id = $dmUserInstance->getId();
        $q->addWhere(sprintf('%s.dm_user_id = ?', $alias), $user_id);

        return $this;
    }
}