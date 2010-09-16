<?php


class ClientLiabilitiesTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('ClientLiabilities');
    }
}