<?php


class ClientFileTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('ClientFile');
    }


    public function forDepartmentName($depName, Doctrine_Query $q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('ClientFile e');
        }
        $alias = $q->getRootAlias();
        $q->leftJoin(sprintf('%s.Department d', $alias));
        $q->andWhere('d.name = ?', $depName);
        return $q;
    }

    public function getActiveEventsR(Doctrine_Query $q = null)
    {
        return $this->addActiveEventsQuery($q)->execute();
    }
    public function getActiveEventsA(Doctrine_Query $q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('ClientFile e');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.is_active = ?', $alias), 1);

        return $q;
    }

    public function starting($start, Doctrine_Query $q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('ClientFile e');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.date_start >= ?', $alias), $start);

        return $q;
    }

    public function filterByStatus($status, Doctrine_Query $q = null){

        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('ClientFile e');
        }
        if ($status == -1) return $q;

        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.status_id = ?', $alias), $status);

        return $q;
    }
    public function filterByCategory($category, Doctrine_Query $q = null){

        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('ClientFile e');
        }
        if ($category == -1) return $q;

        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.category_id = ?', $alias), $category);

        return $q;
    }


    public function sortBy($column_name, $sort_type = 'ASC', Doctrine_Query $q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('ClientFile e');
        }
        $alias = $q->getRootAlias();

        $q->orderBy(sprintf('%s.%s %s', $alias, $column_name, $sort_type));

        return $q;
    }
    public function ending($end, Doctrine_Query $q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('ClientFile e');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.date_start <= ?', $alias), $end);

        return $q;
    }
    public function forUser($user_id, Doctrine_Query $q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('ClientFile e');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.assigned_to = ?', $alias), $user_id);

        return $q;
    }

    public function countActiveEvents(Doctrine_Query $q = null)
    {
        return $this->addActiveEventsQuery($q)->count();
    }

    public function addActiveEventsQuery(Doctrine_Query $q = null)
    {
        if(is_null($q))
        {
            $q = Doctrine_Query::create()->from('ClientFile e');
        }

        $alias = $q->getRootAlias();

        $q->andWhere($alias . '.is_active = ?', 1);

        return $q;
    }
}