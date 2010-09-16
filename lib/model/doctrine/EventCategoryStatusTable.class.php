<?php


class EventCategoryStatusTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EventCategoryStatus');
    }
}