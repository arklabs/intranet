<?php


class ExpenseTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Expense');
    }
}