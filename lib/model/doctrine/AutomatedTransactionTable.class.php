<?php


class AutomatedTransactionTable extends TransactionTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('AutomatedTransaction');
    }
}