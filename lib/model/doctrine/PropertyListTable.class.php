<?php


class PropertyListTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PropertyList');
    }
}