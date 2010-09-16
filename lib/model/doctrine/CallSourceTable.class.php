<?php


class CallSourceTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CallSource');
    }
}