<?php


class ZipCodeTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('ZipCode');
    }
    public function like($pattern, Doctrine_Query &$q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('ZipCode zc');
        }
        $alias = $q->getRootAlias();

        $q->addWhere(sprintf('%s.zip_code LIKE "%s"', $alias, "%".$pattern."%"));

        return $this;
    }
    public function limit($limit, &$q){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('ZipCode zc');
        }
        $alias = $q->getRootAlias();
        $q->limit($limit);
        return $this;
    }
}