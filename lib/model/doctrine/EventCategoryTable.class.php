<?php


class EventCategoryTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EventCategory');
    }
    public function getAll(){
        return Doctrine::getTable('EventCategory')->createQuery()->execute();
    }

    
}