<?php


class IncommingCallTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('IncommingCall');
    }
    public function starting($start, Doctrine_Query &$q = null){
        if (is_null($q)){
            $q = Doctrine_Query::create()
            ->from('IncommingCall i');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.created_at >= ?', $alias), $start);

        return $this;
    }
    public function ending($end, Doctrine_Query &$q = null){
        if (is_null($q)){
            $q = Doctrine_Query::create()
            ->from('IncommingCall i');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.created_at <= ?', $alias), $end);

        return $this;
    }
    // most be called first of all other queries
   public function groupByReason(Doctrine_Query &$q = null){
       $q = Doctrine_Query::create()
            ->select('COUNT(i.id) AS rcall_count, i.*, ir.*')
            ->from('IncommingCall i')
            ->leftJoin('i.IncommingCallReason ir')
            ->groupBy('i.reason_id');
       return $this;
   }
}