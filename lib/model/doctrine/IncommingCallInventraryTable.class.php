<?php


class IncommingCallInventraryTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('IncommingCallInventrary');
    }
}