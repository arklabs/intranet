<?php


class CompanyExpensesTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CompanyExpenses');
    }
}