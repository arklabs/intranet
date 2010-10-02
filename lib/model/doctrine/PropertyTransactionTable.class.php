<?php


class PropertyTransactionTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PropertyTransaction');
    }
}