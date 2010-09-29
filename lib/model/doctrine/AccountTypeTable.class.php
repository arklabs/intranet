<?php


class AccountTypeTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('AccountType');
    }
}