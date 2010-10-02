<?php


class IncommingCallUsualCallersTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('IncommingCallUsualCallers');
    }
}