<?php


class IncommingCallProspectInterestTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('IncommingCallProspectInterest');
    }
}