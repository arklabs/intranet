<?php


class PropertyPaymentTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PropertyPayment');
    }
}