<?php


class IncommingCallTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('IncommingCall');
    }
	public function starting($start, Doctrine_Query &$q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('IncommingCall i');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.created_at >= ?', $alias), $start);

        return $this;
    }
	public function ending($end, Doctrine_Query &$q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('IncommingCall i');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.created_at <= ?', $alias), $end);

        return $this;
    }
}