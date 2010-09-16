<?php


class IncommingCallTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('IncommingCall');
    }
}