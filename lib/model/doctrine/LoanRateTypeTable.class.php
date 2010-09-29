<?php


class LoanRateTypeTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('LoanRateType');
    }
}