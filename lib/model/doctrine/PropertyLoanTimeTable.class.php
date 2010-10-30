<?php


class PropertyLoanTimeTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PropertyLoanTime');
    }
}