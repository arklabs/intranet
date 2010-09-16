<?php


class EventStatusTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EventStatus');
    }
    public function getAll(){
        return Doctrine::getTable('EventStatus')->createQuery()->execute();
    }
    public function getDefaultValue(){
    	return Doctrine::getTable('EventStatus')->createQuery('s')->where('s.name = ?', 'Pendiente')->fetchOne()->getId();
    }
}