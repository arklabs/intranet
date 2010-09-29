<?php


class EventFeedBackTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EventFeedBack');
    }
}