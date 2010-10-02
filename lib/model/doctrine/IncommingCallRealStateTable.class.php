<?php


class IncommingCallRealStateTable extends IncommingCallTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('IncommingCallRealState');
    }
}