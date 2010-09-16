<?php


class LiabilitiesTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Liabilities');
    }
}