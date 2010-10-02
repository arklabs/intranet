<?php


class IncommingCallProspectTable extends IncommingCallTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('IncommingCallProspect');
    }
}