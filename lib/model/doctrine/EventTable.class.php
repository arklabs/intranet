<?php


class EventTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Event');
    }

    public function getActiveEventsR(Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        return $this->addActiveEventsQuery($q, $dstart, $dend)->execute();
    }

    //Esta se va...
    public function getActiveCitasR(Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        $c = $this->addActiveEventsQuery($q, $dstart, $dend);
        $alias = $c->getRootAlias();
        return $c->addWhere($alias . '.category_id = ?', 1)->execute();
    }

    public function addActiveEventsByCategoryQuery($catId, Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        $c = $this->addActiveEventsQuery($q, $dstart, $dend);
        $alias = $c->getRootAlias();
        $c->addWhere($alias . '.category_id = ?', $catId);

        return $c;
    }

    public function getActiveEventsByUser($userId, Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        return $this->addActiveEventsByUseryQuery($userId, $q, $dstart, $dend)->execute();
    }

    public function getActiveEventsByUserCount($userId, Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        return $this->addActiveEventsByUserQuery($userId, $q, $dstart, $dend)->count();
    }
    
    public function addActiveEventsByUserQuery($userId, Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        $u = $this->addActiveEventsQuery($q, $dstart, $dend);
        $alias = $u->getRootAlias();
        $u->addWhere($alias . '.dm_user_id = ?', $userId);

        return $u;
    }
    
    public function getActiveEventsByCategory($catId, Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        return $this->addActiveEventsByCategoryQuery($catId, $q, $dstart, $dend)->execute();
    }

    public function getActiveEventsByCategoryCount($catId, Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        return $this->addActiveEventsByCategoryQuery($catId, $q, $dstart, $dend)->count();
    }

    public function addActiveEventsByStatusQuery($statusId, Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        $s = $this->addActiveEventsQuery($q, $dstart, $dend);
        $alias = $s->getRootAlias();
        $s->addWhere($alias . '.status_id = ?', $statusId);

        return $s;
    }

    public function getActiveEventsByStatus($statusId, Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        return $this->addActiveEventsByStatusQuery($statusId, $q, $dstart, $dend)->execute();
    }

    public function getActiveEventsByStatusCount($statusId, Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        return $this->addActiveEventsByStatusQuery($statusId, $q, $dstart, $dend)->count();
    }


    public function getActiveEventsA(Doctrine_Query $q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('Event e');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.is_active = ?', $alias), 1);

        return $this;
    }

    public function nonPending(Doctrine_Query &$q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('Event e');
        }
        $alias = $q->getRootAlias();
        $defStatus = Doctrine::getTable('EventStatus')->getDefaultValue();
        $q->andWhere(sprintf('%s.status_id <> ?', $alias), $defStatus);
        return $this;
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

    public function filterByStatus($status, Doctrine_Query &$q = null){
        
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('Event e');
        }
        if ($status == -1) return $this;
        
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.status_id = ?', $alias), $status);

        return $this;
    }
    public function filterByCategory($category, Doctrine_Query &$q = null){

        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('Event e');
        }
        if ($category == -1) return $this;

        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.category_id = ?', $alias), $category);

        return $this;
    }
    public function filterByCategoryName($categoryName, Doctrine_Query &$q = null){
       if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('Event e');
        }
        $alias = $q->getRootAlias();

        $q->leftJoin($alias.'.EventCategory cat')->andWhere('cat.name = ?', $categoryName);
        return $this;
    }

    public function sortBy($column_name, $sort_type = 'ASC', Doctrine_Query &$q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('Event e');
        }
        $alias = $q->getRootAlias();

        $q->orderBy(sprintf('%s.%s %s', $alias, $column_name, $sort_type));

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
    public function forUser($user_id, Doctrine_Query &$q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('Event e');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.dm_user_id = ?', $alias), $user_id);
        $q->orWhere(sprintf('%s.created_by = ?', $alias), $user_id);
        
        return $this;
    }

    public function countActiveEvents(Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        return $this->addActiveEventsQuery($q, $dstart, $dend)->count();
    }

    public function countActiveEventsByStatus($statusId, Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        return $this->addActiveEventsByStatusQuery($statusId, $q, $dstart, $dend)->count();

    }

    public function addActiveEventsQuery(Doctrine_Query $q = null, $dstart = null, $dend = null )
    {
        if(is_null($q))
        {
            $q = Doctrine_Query::create()->from('Event e');
        }

        $alias = $q->getRootAlias();

        $q->andWhere($alias . '.is_active = ?', 1);

        if(isset($dstart) && isset($dend))
        {
            $q->andWhere($alias . '.date_start >= ?', $dstart)
                    ->andWhere($alias . '.date_end < ?', $dend);
        }
        
        return $q;
    }

    public function addEventsAssignedToCountQuery(Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        $q = $this->addActiveEventsQuery($q, $dstart, $dend );
        $alias = $q->getRootAlias();
                    return $q->groupBy('dm_user_id')->addSelect('COUNT('.$alias . '.dm_user_id) as num_events')
                    ->orderBy('num_events DESC');
    }

    public function addEventsCreatedByCountQuery(Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
         $q = $this->addActiveEventsQuery($q, $dstart, $dend);
         $alias = $q->getRootAlias();
         return $q->groupBy('created_by')->addSelect('COUNT('.$alias . '.created_by) as num_events')
                    ->orderBy('num_events DESC');
    }

    public function getEventsByCategoryAssignedTo($catId, Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        $q = $this->addActiveEventsByCategoryQuery($catId, $q, $dstart, $dend);
        return $this->addEventsAssignedToCountQuery($q, $dstart, $dend)->execute();

    }

    public function getEventsByCategoryCreatedBy($catId, Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        $q = $this->addActiveEventsByCategoryQuery($catId, $q, $dstart, $dend);
        return $this->addEventsCreatedByCountQuery($q, $dstart, $dend)->execute();

    }

    public function getDatesByCity(Doctrine_Query $q = null, $dstart = null, $dend = null)
    {
        $q = $this->addActiveEventsByCategoryQuery(1, $q, $dstart, $dend);
        $alias = $q->getRootAlias();
        $q->addSelect('COUNT('.$alias . '.city_id) as num_events')->groupBy('city_id')
                ->orderBy('num_events DESC');
        return $q->execute();

    }
    

}
