<?php


class UserAvailabilityTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('UserAvailability');
    }
    public static function addAvailabilityRecord($userId, $day){
        $er = Doctrine::getTable('UserAvailability')->createQuery('u')->where('u.user_id = ?', $useId)->andWhere('u.timestamp = ?', $day)->execute();
        if (!count($er)){
            $record = Doctrine::getTable('UserAvailability')->create(array('user_id'=>$userId, 'timestamp'=>$day));
            $record->save();
        }
    }
    public static function removeAvailabilityRecord($userId, $day){
        try{
        Doctrine_Query::create()
        ->delete('UserAvailability')
        ->where('user_id = ?', $userId)
        ->addWhere('timestamp = ?', $day)
        ->execute();
        }catch(Exception $e){
            echo $e->getMessage(); die;
        }
    }
    public function starting($start, Doctrine_Query &$q = null){
        
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('UserAvailability e');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.timestamp >= ?', $alias), $start);
        return $this;
    }

     public function ending($end, Doctrine_Query &$q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('UserAvailability e');
        }
        $alias = $q->getRootAlias();

        $q->andWhere(sprintf('%s.timestamp <= ?', $alias), $end);

        return $this;
    }

    public function forUser($dmUserInstance, Doctrine_Query &$q = null){
        if (is_null($q))
        {
            $q = Doctrine_Query::create()
            ->from('UserAvailability e');
        }
        $alias = $q->getRootAlias();
        $user_id = $dmUserInstance->getId();
        $q->addWhere(sprintf('%s.user_id = ?', $alias), $user_id);
        return $this;
    }

    public static function getAvailableAgents($dateTimestamp){
        $agents = Doctrine::getTable('UserAvailability')->createQuery('u')
             ->leftJoin('u.DmUser user')
             ->where('user.type = ?', 'Agent')
             ->andWhere('u.timestamp = ?', $dateTimestamp)
             ->groupBy('user_id')
             ->execute();

        $choicesListResultWay = array();
        foreach ($agents as $a){
            $choicesListResultWay[$a->getUserId()] = $a->getDmUser();
        }

        return $choicesListResultWay;
    }

}