<?php


class IncommingCallReasonTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('IncommingCallReason');
    }
}