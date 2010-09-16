<?php


class ClientIncomesTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('ClientIncomes');
    }
}