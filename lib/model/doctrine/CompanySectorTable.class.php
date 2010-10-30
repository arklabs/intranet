<?php


class CompanySectorTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CompanySector');
    }
}