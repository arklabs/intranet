<?php


class PropertyTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Property');
    }
    public function getPropertiesOfClient($clientId){
        return Doctrine::getTable('Property')->createQuery('p')->where('p.client_id = ?', $clientId)->execute();
    }

    public function like($pattern, Doctrine_Query $q = null){
        $q = Doctrine_Query::create()
        ->from('Property e')
         ->leftJoin('e.Client c')
         ->leftJoin('e.Address a')
          ->addWhere(sprintf('c.first_name LIKE "%s" OR c.last_name LIKE "%s" OR a.address LIKE "%s"', "%".$pattern."%", "%".$pattern."%", "%".$pattern."%"));
        return $q;
    }
    
}