<?php


class CompanyRecurringExpensesTable extends CompanyExpensesTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CompanyRecurringExpenses');
    }
}