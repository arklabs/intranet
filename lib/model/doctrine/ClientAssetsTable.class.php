<?php


class ClientAssetsTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('ClientAssets');
    }
}