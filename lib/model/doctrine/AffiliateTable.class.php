<?php


class AffiliateTable extends DmUserTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Affiliate');
    }
}