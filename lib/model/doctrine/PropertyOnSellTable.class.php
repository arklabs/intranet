<?php


class PropertyOnSellTable extends PropertyListTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PropertyOnSell');
    }
}