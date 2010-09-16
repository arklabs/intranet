<?php


class ClientExpensesTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('ClientExpenses');
    }
}