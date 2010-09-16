<?php


class AgentTable extends DmUserTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Agent');
    }

    public function getWithEvents($startDate, $endDate)
    {
        $q = $this->createQuery('a')
            ->leftJoin('a.Events e')
            ->where('e.date_start >= ?', $startDate)
            ->andWhere('e.date_end < ?', $endDate);
        
        return $q->execute();
    }

}